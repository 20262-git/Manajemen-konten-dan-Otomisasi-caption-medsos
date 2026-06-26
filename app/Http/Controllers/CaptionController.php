<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CaptionController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'keywords'     => 'required|string',
            'platform'     => 'required|in:instagram,tiktok',
            'tone'         => 'required|in:lucu,formal,estetik',
            'language'     => 'required|in:id,en',
        ]);

        $prompt = $this->buildPrompt($request);

        try {
           $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
    'Content-Type' => 'application/json',
])
->timeout(45)
->post(
    env('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'),
    [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt,
            ]
        ],
        'temperature' => 0.85,
        'max_tokens' => 1500,
    ]
);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Groq API Error: ' . $response->body()
                ], 500);
            }

            $caption = $response->json('choices.0.message.content');

            return response()->json([
                'success' => true,
                'caption' => $caption ?? 'Maaf, tidak bisa generate caption saat ini.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke Grok AI. Cek API Key atau internet.'
            ], 500);
        }
    }

    private function buildPrompt(Request $request)
    {
        $toneMap = [
            'lucu'    => 'lucu, santai, banyak emoji, gaya anak muda, viral',
            'formal'  => 'profesional, meyakinkan, sopan, elegan',
            'estetik' => 'estetis, puitis, aesthetic, lembut, inspiring'
        ];

        $lang = $request->language === 'id' ? 'Bahasa Indonesia' : 'English';

        return "Buatkan caption yang menarik untuk {$request->platform} tentang produk:\n\n"
             . "Nama Produk: {$request->product_name}\n"
             . "Keyword: {$request->keywords}\n"
             . "Tone: {$toneMap[$request->tone]}\n"
             . "Bahasa: {$lang}\n\n"
             . "Berikan 3 variasi caption yang siap posting (setiap caption maksimal 3-4 baris).";
    }
}