<?php

namespace App\Http\Controllers;

use App\Models\PostSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CaptionController extends Controller
{
    // ... method generate tetap sama seperti sebelumnya ...

    public function saveToSchedule(Request $request)
    {
        $request->validate([
            'caption' => 'required|string',
            'platform' => 'required|in:instagram,tiktok',
            'scheduled_at' => 'required|date|after:now',
        ]);

        PostSchedule::create([
            'user_id' => auth()->id(),
            'platform' => $request->platform,
            'caption' => $request->caption,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Caption berhasil disimpan ke jadwal!'
        ]);
    }
}