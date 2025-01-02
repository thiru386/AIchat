<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIchatService {
    public function sendMessage($message) {
        try {

            $response = Http::withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    "model"=> "gpt-4o-mini",
                    "store"=> true,
                    'messages' => [
                        ['role' => 'user', 'content' => $message],
                    ],
                ]);
    

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('OpenAI API request failed', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                return ['choices' => [['message' => ['content' => 'Error: Could not get response from OpenAI']]]];
            }
        } catch (\Exception $e) {
            Log::error('Exception during OpenAI API request', ['exception' => $e->getMessage()]);
            return ['choices' => [['message' => ['content' => 'Error: Could not get response from OpenAI']]]];
        }
    }
}
