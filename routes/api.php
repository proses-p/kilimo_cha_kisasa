<?php

use App\Http\Controllers\Api\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Api\Admin\CropController as AdminCropController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\FarmController as AdminFarmController;
use App\Http\Controllers\Api\Admin\FarmingTipController as AdminFarmingTipController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CropActivityController;
use App\Http\Controllers\Api\CropController;
use App\Http\Controllers\Api\FarmController;
use App\Http\Controllers\Api\WeatherController;
use Illuminate\Support\Facades\Request;
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

    // Admin routes - protected by auth and admin middleware
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // users management
        Route::apiResource('users', AdminUserController::class)->only(['index','store','show','update','destroy']);

        // farmers / farms management
        Route::apiResource('farms', AdminFarmController::class);

        // crops
        Route::apiResource('crops', AdminCropController::class);

        // farming tips
        Route::apiResource('tips', AdminFarmingTipController::class);

        // announcements
        Route::apiResource('announcements', AdminAnnouncementController::class);
    });
});

// routes protected with token to read notifications
Route::middleware('auth:sanctum')->get('/notifications', function (Request $request) {
    return response()->json($request->user()->notifications);
});


