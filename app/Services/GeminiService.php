<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiKey;
    protected string $url;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        $this->url =
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key={$this->apiKey}";
    }
    // test models
    public function models()
{
    $response = Http::get(
        "https://generativelanguage.googleapis.com/v1beta/models?key={$this->apiKey}"
    );

    $models = collect($response->json()['models'])
        ->filter(function($model){
            return in_array(
                'generateContent',
                $model['supportedGenerationMethods'] ?? []
            );
        })
        ->pluck('name');

    dd($models->toArray());
}


    public function generate(string $prompt): ?string
    {
        $response = Http::timeout(60)
            ->post($this->url, [
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" => $prompt
                            ]
                        ]
                    ]
                ]
            ]);


        if (!$response->successful()) {

            \Log::error("Gemini Error", [
                "status" => $response->status(),
                "body" => $response->body(),
            ]);

            return null;
        }


        /*return data_get(
            $response->json(),
            'candidates.0.content.parts.0.text'
        );
        */

        $json = $response->json();

return $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }
}
