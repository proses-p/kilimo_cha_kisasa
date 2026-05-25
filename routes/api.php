<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmController;
use App\Http\Controllers\Api\CropController;
use App\Http\Controllers\Api\CropActivityController;
use App\Http\Controllers\Api\WeatherController;


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

// public routes which no need for a token
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



// protected routes with tokens
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // farms crud
    Route::apiResource('farms', FarmController::class);

    // crop nested under farms
    Route::apiResource('farms.crops', CropController::class);

    // activities nested under crops
    Route::apiResource('crops.activities', CropActivityController::class)
          ->only(['index', 'store', 'destroy']);

    // weather routes
    Route::get('farms/{farm}/weather', [WeatherController::class, 'current']);
    Route::get('farms/{farm}/weather/forecast', [WeatherController::class, 'forecast']);
});


