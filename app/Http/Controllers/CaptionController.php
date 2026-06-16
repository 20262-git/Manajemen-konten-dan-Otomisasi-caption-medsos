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
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent', [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 1000,
                ]
            ]);

            $caption = $response->json('candidates.0.content.parts.0.text') 
                       ?? 'Maaf, gagal generate caption. Coba lagi nanti.';

            return response()->json([
                'success' => true,
                'caption' => $caption
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildPrompt(Request $request)
    {
        $toneMap = [
            'lucu'    => 'lucu, santai, menghibur, dan viral',
            'formal'  => 'profesional, meyakinkan, dan elegan',
            'estetik' => 'estetis, puitis, aesthetic, dan inspiring'
        ];

        $lang = $request->language === 'id' ? 'Bahasa Indonesia' : 'Bahasa Inggris';

        return "Buatkan caption yang menarik untuk {$request->platform} tentang produk: {$request->product_name}.\n"
             . "Keyword: {$request->keywords}.\n"
             . "Tone/gaya: {$toneMap[$request->tone]}.\n"
             . "Bahasa: {$lang}.\n\n"
             . "Berikan 3 variasi caption yang siap posting (maksimal 2-4 baris setiap caption).";
    }
}