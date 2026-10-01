<?php

namespace App\Http\Controllers;

use App\Exceptions\ChatbotException;
use App\Http\Requests\AskChatbotRequest;
use App\Services\Chatbot\ChatbotService;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    /** Terima pesan dari widget chat dan kembalikan balasan AI sebagai JSON. */
    public function ask(AskChatbotRequest $request, ChatbotService $chatbot): JsonResponse
    {
        try {
            $reply = $chatbot->jawab($request->pesan(), $request->riwayat());
        } catch (ChatbotException $e) {
            return response()->json(['reply' => $e->getMessage()], $e->status());
        }

        return response()->json(['reply' => $reply]);
    }
}