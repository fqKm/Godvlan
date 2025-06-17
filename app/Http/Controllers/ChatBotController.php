<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Mockery\Exception;

class ChatBotController extends Controller
{
    public function ask(Request $request):JsonResponse{
        $user = Auth::user();
        $request->validate(['prompt' => 'required|string']);
        $transaction = Transaction::where('user_id', $user->id)->get();
        $APIkey = env('GROQ_API_KEY');
        $ApiUrl = "https://api.groq.com/openai/v1/chat/completions";
        $content = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'user', 'content' => 'ini adalah data transaksi saya'.$transaction.$request->prompt]
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $APIkey,
                'Content-Type' => 'application/json',
            ])->post($ApiUrl, $content);
            $json = $response->json();
            $message = $json['choices'][0]['message']['content'] ?? 'No response';
            return response()->json(['data' => $message]);
        } catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
