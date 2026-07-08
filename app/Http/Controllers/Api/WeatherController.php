<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\WeatherService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    //
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService, RecommendationService $recommendationService) {
        $this->weatherService = $weatherService;
        $this->recommendationService = $recommendationService;
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
                $farm->longitude
            );

            $recommendations = $this->recommendationService->generate(
                $weather,
                $farm
            );

            // ongeza ushauri wa kilimo
            $weather['farming_advice'] = $this->weatherService->getFarmingAdvice($weather);

            return response()->json([
                'success' => true,
                'farm' => $farm->name,
                'data' => $weather,
            ]);

            return response()->json([
                'success' => true,
                'weather' => $weather,
                'recommendations' => $recommendations
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Weather Error: '.$e->getMessage());
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
                $farm->longitude
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
