<?php

namespace App\Services;

class RecommendationService
{
    public function generate(array $weather, $farm = null)
    {
        $recommendations = [];

        $temperature = $weather['temperature'] ?? 0;
        $humidity    = $weather['humidity'] ?? 0;
        $rain        = $weather['rain'] ?? 0;
        $wind        = $weather['wind_speed'] ?? 0;

        /*
        =====================================
        TEMPERATURE
        =====================================
        */

        if ($temperature >= 35) {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => '🌡️',
                'title' => 'Joto Kali',
                'message' => 'Joto ni kubwa sana. Mwagilia mazao mapema asubuhi au jioni.'
            ];
        }

        if ($temperature >= 28 && $temperature < 35) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => '☀️',
                'title' => 'Joto la Kawaida',
                'message' => 'Hakikisha udongo haukauki sana.'
            ];
        }

        /*
        =====================================
        HUMIDITY
        =====================================
        */

        if ($humidity < 40) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => '💧',
                'title' => 'Unyevu Mdogo',
                'message' => 'Maji yanahitajika kwa baadhi ya mazao.'
            ];
        }

        if ($humidity > 85) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => '🍄',
                'title' => 'Unyevu Mkubwa',
                'message' => 'Kuna hatari ya magonjwa ya fangasi.'
            ];
        }

        /*
        =====================================
        RAIN
        =====================================
        */

        if ($rain > 10) {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => '🌧️',
                'title' => 'Mvua Kubwa',
                'message' => 'Usimwagilie leo. Epuka kuweka mbolea.'
            ];
        }

        if ($rain > 0 && $rain <= 10) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => '🌦️',
                'title' => 'Mvua Nyepesi',
                'message' => 'Mvua inaweza kusaidia kupunguza umwagiliaji.'
            ];
        }

        /*
        =====================================
        WIND
        =====================================
        */

        if ($wind > 8) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => '💨',
                'title' => 'Upepo Mkali',
                'message' => 'Epuka kunyunyizia dawa leo.'
            ];
        }

        /*
        =====================================
        DEFAULT
        =====================================
        */

        if (count($recommendations) == 0) {

            $recommendations[] = [
                'type' => 'success',
                'icon' => '✅',
                'title' => 'Hali Nzuri',
                'message' => 'Hali ya hewa ni nzuri kwa shughuli nyingi za shamba.'
            ];
        }

        return $recommendations;
    }
}
