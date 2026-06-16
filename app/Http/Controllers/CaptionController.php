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
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ])->timeout(30)->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.85,
                        'maxOutputTokens' => 1200,
                    ]
                ]
            );

            $caption = $response->json('candidates.0.content.parts.0.text') 
                       ?? "❌ Gemini API tidak dapat menghasilkan caption.";

            return response()->json([
                'success' => true,
                'caption' => $caption
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Gemini API: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildPrompt(Request $request)
    {
        $toneMap = [
            'lucu'    => 'lucu, santai, menghibur, emoji banyak, gaya anak muda, viral',
            'formal'  => 'profesional, meyakinkan, elegan, dan informatif',
            'estetik' => 'estetis, puitis, aesthetic, lembut, inspiring'
        ];

        $lang = $request->language === 'id' ? 'Bahasa Indonesia' : 'English';

        return "Buatkan caption Instagram/TikTok yang menarik untuk produk berikut:\n\n"
             . "Nama Produk: {$request->product_name}\n"
             . "Keyword: {$request->keywords}\n"
             . "Platform: {$request->platform}\n"
             . "Tone: {$toneMap[$request->tone]}\n"
             . "Bahasa: {$lang}\n\n"
             . "Berikan **3 variasi caption** yang siap posting (setiap caption maksimal 3-4 baris).";
    }
}