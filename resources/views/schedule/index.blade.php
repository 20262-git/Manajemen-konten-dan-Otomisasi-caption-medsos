@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📅 Kalender Jadwal Posting</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola semua jadwal postingan kamu</p>
        </div>
        <a href="{{ route('schedule.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-semibold flex items-center gap-2 transition">
            + Tambah Jadwal Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-2xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-5 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Platform</th>
                    <th class="px-6 py-5 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal & Waktu</th>
                    <th class="px-6 py-5 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Caption</th>
                    <th class="px-6 py-5 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-5 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-5">
                        <span class="inline-flex items-center gap-2 font-medium">
                            {{ $schedule->platform === 'instagram' ? '📸 Instagram' : '🎵 TikTok' }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-sm">
                        <div class="font-medium">{{ $schedule->scheduled_at->format('d M Y') }}</div>
                        <div class="text-gray-500">{{ $schedule->scheduled_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="line-clamp-2 text-sm text-gray-700 dark:text-gray-300">
                            {{ Str::limit($schedule->caption, 90) }}
                        </div>
                        <button onclick="copyCaption('{{ addslashes($schedule->caption) }}')" 
                                class="text-xs text-blue-600 hover:text-blue-700 mt-1">
                            📋 Copy
                        </button>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-4 py-1.5 text-xs font-medium rounded-full 
                            {{ $schedule->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($schedule->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <a href="{{ route('schedule.edit', $schedule) }}" 
                           class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                        <form action="{{ route('schedule.destroy', $schedule) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-20 text-gray-500">
                        Belum ada jadwal posting.<br>
                        <a href="{{ route('schedule.create') }}" class="text-blue-600 hover:underline">Buat jadwal pertama sekarang →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function copyCaption(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Caption berhasil disalin!');
    });
}
</script>
@endsection