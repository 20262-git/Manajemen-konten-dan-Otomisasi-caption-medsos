@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">📅 Kalender Jadwal Posting</h1>
        <a href="{{ route('schedule.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left">Platform</th>
                    <th class="px-6 py-4 text-left">Tanggal & Waktu</th>
                    <th class="px-6 py-4 text-left">Caption</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr class="border-t">
                    <td class="px-6 py-4">
                        <span class="capitalize font-medium">{{ $schedule->platform }}</span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->scheduled_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-md truncate">{{ Str::limit($schedule->caption, 80) }}</div>
                        <button onclick="copyCaption('{{ addslashes($schedule->caption) }}')" 
                                class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                            📋 Copy Caption
                        </button>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full 
                            {{ $schedule->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($schedule->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('schedule.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('schedule.destroy', $schedule) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Yakin hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-500">
                        Belum ada jadwal posting.
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