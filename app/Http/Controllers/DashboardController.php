<?php

namespace App\Http\Controllers;

use App\Models\PostSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        return view('dashboard', [
            'totalSchedules'   => PostSchedule::where('user_id', $userId)->count(),
            'pendingSchedules' => PostSchedule::where('user_id', $userId)->where('status', 'pending')->count(),
            'postedSchedules'  => PostSchedule::where('user_id', $userId)->where('status', 'posted')->count(),
            'recentSchedules'  => PostSchedule::where('user_id', $userId)->latest()->take(5)->get(),
        ]);
    }
}