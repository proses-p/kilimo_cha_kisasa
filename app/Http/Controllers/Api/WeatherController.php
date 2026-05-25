<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    //
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService) {
        $this->weatherService = $weatherService;
    }

    public function current(Request $request, Farm $farm) {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }

        // make sure a farm has gps
        if (!$farm->latitude || !$farm->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Shamba hili halina GPS. Hariri shamba na uweke latitude na longitude',
            ], 422);
        }

        try {
            $weather = $this->weatherService->getCurrentWeather(
                $farm->latitude,
                $farm->longtude
            );

            // ongeza ushauri wa kilimo
            $weather['farming_advice'] = $this->weatherService->getFarmingAdvice($weather);

            return response()->json([
                'success' => true,
                'farm' => $farm->name,
                'data' => $weather,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function forecast(Request $request, Farm $farm) {
        if ($farm->user_id !== $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'No option to change this farm',
            ], 403);
        }

        if (!$farm->latitude || !$farm->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Shamba hili halina GPS. Hariri shamba na uweke latitude na longitude',
            ], 422);
        }

        try {
            $forecast = $this->weatherService->getForecast(
                $farm->latitude,
                $farm->longtude
            );

            return response()->json([
                'success' => true,
                'farm' => $farm->name,
                'data' => $forecast,
            ]);
    }  catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
