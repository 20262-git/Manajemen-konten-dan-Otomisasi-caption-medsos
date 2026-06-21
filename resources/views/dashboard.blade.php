@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Social Media Planner</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> 👋</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('caption.generator') }}" 
               class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl font-semibold hover:shadow-lg transition flex items-center gap-2">
                ✨ Generate Caption
            </a>
            <a href="{{ route('schedule.create') }}" 
               class="px-6 py-3 border-2 border-gray-300 rounded-2xl font-semibold hover:bg-gray-50 transition">
                + Tambah Jadwal
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow">
            <div class="text-6xl mb-4">📅</div>
            <p class="text-5xl font-bold">{{ $totalSchedules ?? 0 }}</p>
            <p class="text-gray-500">Total Jadwal</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow">
            <div class="text-6xl mb-4">⏳</div>
            <p class="text-5xl font-bold text-amber-500">{{ $pendingSchedules ?? 0 }}</p>
            <p class="text-gray-500">Pending</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow">
            <div class="text-6xl mb-4">✅</div>
            <p class="text-5xl font-bold text-emerald-500">{{ $postedSchedules ?? 0 }}</p>
            <p class="text-gray-500">Sudah Posting</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow">
            <div class="text-6xl mb-4">🚀</div>
            <p class="text-5xl font-bold text-purple-600">2</p>
            <p class="text-gray-500">Platform Aktif</p>
        </div>
    </div>

    <!-- Recent Schedules -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Jadwal Terbaru</h2>
            <a href="{{ route('schedule.index') }}" class="text-blue-600 hover:underline text-sm">Lihat Semua →</a>
        </div>

        @if($recentSchedules->isEmpty())
            <div class="text-center py-16 text-gray-500">
                Belum ada jadwal posting.<br>
                <a href="{{ route('schedule.create') }}" class="text-blue-600 hover:underline">Buat jadwal pertama</a>
            </div>
        @else
            <div class="space-y-5">
                @foreach($recentSchedules as $schedule)
                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-5 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <span class="text-4xl">{{ $schedule->platform == 'instagram' ? '📸' : '🎵' }}</span>
                        <div>
                            <span class="uppercase text-xs font-bold px-4 py-1 bg-white dark:bg-gray-600 rounded-full">
                                {{ $schedule->platform }}
                            </span>
                            <p class="mt-2 text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ Str::limit($schedule->caption, 85) }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">{{ $schedule->scheduled_at->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $schedule->scheduled_at->format('H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection