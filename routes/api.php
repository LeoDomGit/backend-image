<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/image',[ImageController::class,'image']);
Route::post('/removebackground',[ImageController::class,'RemoveBackground']);

Route::post('/generate',[ImageController::class,'generateImage']);
// Route::post('/getGenerate',[ImageController::class,'getGenerate']);
Route::post('/image/process', [ImageController::class, 'process']);
