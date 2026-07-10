<?php

namespace App\Http\Controllers;

use App\Services\AIContextService;
use App\Services\AIPromptService;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected GeminiService $gemini;
    protected AIPromptService $prompt;
    protected AIContextService $context;

    public function __construct(
        GeminiService $gemini,
        AIPromptService $prompt,
        AIContextService $context
        )
    {
        $this->gemini = $gemini;
        $this->prompt = $prompt;
        $this->context = $context;

    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $user = auth()->user();

        $context = $this->context->build($user);

        $prompt = $this->prompt->build(
            $context,
            $request->message
            );

        $response = $this->gemini->generate($prompt);


        return response()->json([
            'success' => true,
            'reply' => $response
        ]);
    }

}
