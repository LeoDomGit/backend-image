<?php

use App\Http\Controllers\BrandsController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('roles', RolesController::class);
Route::resource('brands', BrandsController::class);
Route::resource('keys', KeyController::class);
Route::resource('features', FeaturesController::class);
