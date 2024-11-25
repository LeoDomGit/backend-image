<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeyController extends Controller
{

    public function __construct()
    {
        $keys = Key::where('api','vanceai')->get();
        $keys1 = Key::where('api','picsart')->get();
        foreach ($keys1 as $key) {
            // Send a request to check the balance
            $response = Http::withHeaders([
                'X-Picsart-API-Key' => $key->token,
                'Accept' => 'application/json',
            ])->get('https://api.picsart.io/tools/1.0/balance');

            if ($response->successful()) {
                $data = $response->json();

                // Check if the 'credits' value is less than 5
                if (isset($data['credits']) && $data['credits'] < 5) {
                    // Delete the key if credits are less than 5
                    $key->delete();
                }
            }
        }
        foreach ($keys as $key) {
            $response = Http::get("https://api-service.vanceai.com/web_api/v1/point", [
                'api_token' => $key->token
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['max_num'], $data['data']['used_num'])) {
                    $maxNum = (float) $data['data']['max_num'];
                    $usedNum = (float) $data['data']['used_num'];
                    if ($maxNum - $usedNum < 10) {
                        $key->delete();
                    }
                }
            }
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keys = Key::all();
        return Inertia::render('Key/Index', ['datakeys' => $keys]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|',
            'token' => 'required|string|',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json([
                'check' => false,
                'msg' => $validator->errors()->first()
            ], 400);
        }

        // Create the new key
        $key = Key::create([
            'email' => $request->email,
            'token' => $request->token,
        ]);

        return response()->json([
            'check' => true,
            'data' => $key
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Key $key)
    {
        // Validate only the fields that are passed (nullable fields)
        $validatedData = $request->validate([
            'email' => 'nullable|string|',
            'token' => 'nullable|string|',
            'api' => 'nullable|string|',
        ]);

        // Update the key with the new data if present
        if ($request->has('email')) {
            $key->email = $request->email;
        }
        if ($request->has('token')) {
            $key->token = $request->token;
        }
        if ($request->has('api')) {
            $key->api = $request->api;
        }
        // Save the updated key
        $key->save();
        $data=Key::all();
        return response()->json([
            'check' => true,
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Key $key)
    {
        $key->delete();

        return response()->json([
            'check' => true,
            'msg' => 'Key deleted successfully.'
        ], 200);
    }
}
