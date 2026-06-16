<?php

namespace App\Http\Controllers;

use App\Models\PostSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = PostSchedule::where('user_id', auth()->id())
            ->orderBy('scheduled_at')
            ->get();

        return view('schedule.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedule.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform'      => 'required|in:instagram,tiktok',
            'caption'       => 'required|string',
            'scheduled_at'  => 'required|date|after:now',
            'image_path'    => 'nullable|string',
        ]);

        PostSchedule::create([
            'user_id'       => auth()->id(),
            'platform'      => $request->platform,
            'caption'       => $request->caption,
            'image_path'    => $request->image_path,
            'scheduled_at'  => $request->scheduled_at,
        ]);

        return redirect()->route('schedule.index')
                         ->with('success', 'Jadwal posting berhasil ditambahkan!');
    }

    public function edit(PostSchedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }
        return view('schedule.edit', compact('schedule'));
    }

    public function update(Request $request, PostSchedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'platform'      => 'required|in:instagram,tiktok',
            'caption'       => 'required|string',
            'scheduled_at'  => 'required|date|after:now',
        ]);

        $schedule->update($request->only(['platform', 'caption', 'scheduled_at']));

        return redirect()->route('schedule.index')
                         ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(PostSchedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }
        $schedule->delete();

        return redirect()->route('schedule.index')
                         ->with('success', 'Jadwal berhasil dihapus!');
    }
}