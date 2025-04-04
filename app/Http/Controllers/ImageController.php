<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    private $apiConfigs = [
        'remove-bg' => [
            'url' => 'https://api.picsart.io/tools/1.0/remove-background',
            'headers' => [
                'X-Picsart-API-Key' => 'your_picsart_key_here',
                'Content-Type' => 'multipart/form-data',
            ],
        ],
        'anime' => [
            'url' => 'https://api.picsart.io/tools/1.0/cartoonizer/anime',
            'headers' => [
                'X-Picsart-API-Key' => 'your_picsart_key_here',
                'Content-Type' => 'multipart/form-data',
            ],
        ],
        'vance-anime' => [
            'url' => 'https://api.vanceai.com/v1/anime',
            'headers' => [
                'apikey' => 'your_vanceai_key_here',
                'Content-Type' => 'multipart/form-data',
            ],
        ],
    ];

    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'type' => 'required|string|in:remove-bg,anime,vance-anime',
        ]);

        $type = $request->input('type');
        $image = $request->file('image');

        $config = $this->apiConfigs[$type] ?? null;
        if (!$config) {
            return response()->json(['error' => 'Invalid transformation type.'], 400);
        }

        // Save the uploaded image temporarily
        $tempPath = $image->store('temp');
        $fullPath = storage_path('app/' . $tempPath);

        // Send image to the selected API
        $response = Http::withHeaders($config['headers'])->attach(
            'image', file_get_contents($fullPath), $image->getClientOriginalName()
        )->post($config['url']);

        // Delete temporary image
        unlink($fullPath);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'API request failed',
                'details' => $response->json(),
            ], 500);
        }

        // Save processed image to public/images
        $imageData = $response->body();
        $filename = time() . '_' . Str::random(10) . '.png';
        $destinationPath = public_path('images/' . $filename);

        file_put_contents($destinationPath, $imageData);

        // Generate URL
        $url = asset('images/' . $filename);

        return response()->json([
            'url' => $url,
        ]);
    }
}
