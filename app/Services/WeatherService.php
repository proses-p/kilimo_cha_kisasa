<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService {
    private string $apiKey;
    private string $baseUrl;

    // dependency injection
    public function __construct() {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = config('services.openweather.base_url');
    }

    // hali ya hewa ya sas kwa latitude na longtude
    public function getCurrentWeather(float $lat, float $lon): array {
        // cache kwa dk 30 kuokoa api calls
        $cacheKey = "weather_current_{$lat}_{$lon}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($lat, $lon) {
            $response = Http::get("{$this->baseUrl}/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric', // celcius
                'lang' => 'sw', 
            ]);

            if ($response->failed()) {
                throw new \Exception('imeshindwa kupata hali ya hewa');
            }
            $data = $response->json();

            // rudisha data iliyopangwa vzr
            return [
                'location' => $data['name'],
                'temperature' => [
                    'current' => round($data['main']['temp']),
                    'feels_like' => round($data['main']['feels_like']),
                    'min' => round($data['main']['temp_min']),
                    'max' => round($data['main']['temp_max']),
                ],
                'humidity' => $data['main']['humidity'],
                'wind_speed' => $data['wind']['speed'],
                'description' => $data['weather'][0]['description'],
                'icon' => $data['weather'][0]['icon'], 
                'icon_url' => "https://openweathermap.org/img/wn/{$data['weather'][0]['icon']}@2x.png",
                'rain' => $data['rain']['1h'] ?? 0,
                'recorded_at' => now()->toDateTimeString(),
            ];


        });


    }

    // utabiri wa siku 5
    public function getForeCast(float $lat, float $lon): array {
        $cacheKey = "weather_forecast_{$lat}_{$lon}";

        return Cache::remember($cacheKey, now()->addHours(3), function () use ($lat, $lon) {
            $response = Http::get("{$this->baseUrl}/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'sw',
                'cnt' => 8, // masaa 24(kila record = masaa 3)
            ]);
            if ($response->failed()) {
                throw new \Exception('imeshindwa kupata utabiri wa hali ya hewa');
            }
            $data = $response->json();

            // panga data vizr

            return collect($data['lists'])->map(function ($item) {
                return [
                    'datetime' => $item['dt_txt'],
                    'temperature' => round($item['main']['temp']),
                    'humidity' => $item['main']['humidity'],
                    'description' => $item['weather'][0]['description'],
                    'icon_url' => "https://openweathermap.org/img/wn/{$item['weather'][0]['icon']}@2x.png",
                    'rain' => $item['rain']['3h'] ?? 0,
                    'wind_speed' => $item['wind']['speed'],
                ];
            })->toArray();
        });
    }

    // ushauri kwa mkulima kulingana na hali ya hewa

    public function getFarmingAdvice(array $weather): string {
        $temp = $weather['temperature']['current'];
        $humidity = $weather['humidity'];
        $rain = $weather['rain'];
        $wind = $weather['wind_speed'];

        // mvua inanyesha
        if ($rain > 0) {
            return "Mvua inanyesha, Subiri imalize kabla ya kumwagilia au kunyunyizia dawa";
        }

        // joto kali sana
        if ($temp > 35) {
            return "joto ni kali sana ({$temp}degree c). Mwagilia mazao asubuhi mapema au jioni.";
        }

        // baridi sana
        if ($temp < 10) {
            return "Baridi kali ({$temp}degree c). Angalia mazao yanayohitaji joto";
        }

        // upepo mkali
        if ($wind > 10) {
            return "Upepo mkali ({$wind} m/s). Epuka kunyunyizia dawa leo, itapotea.";
        }

        // unyevu mkubwa
        if ($humidity > 80) {
            return "Unyevu ni mkubwa ({$humidity}%). Angalia magonjwa ya ukungu kwenye mazao yako.";
        }

        // hali nzru
        return "Hali ya hewa ni nzuri kwa shughuli za shamba leo.";

    }
}