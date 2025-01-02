<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\AIchatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(AIchatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $message = $request->input('message');
            $response = $this->chatService->sendMessage($message);
            $conversation = Conversation::create([
                'message' => $message,
                'response' => $response['choices'][0]['message']['content'],
            ]);

            return response()->json($conversation, 201);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'An error occurred while processing the message.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getConversations()
    {
        return response()->json(Conversation::orderBy('created_at', 'desc')->get());
    }
}
