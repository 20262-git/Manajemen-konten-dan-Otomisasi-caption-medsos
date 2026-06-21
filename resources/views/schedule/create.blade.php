@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-10">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">📅 Tambah Jadwal Posting Baru</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Isi detail postingan kamu</p>
        </div>

        <form method="POST" action="{{ route('schedule.store') }}">
            @csrf

            <div class="space-y-8">
                
                <!-- Platform -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Platform</label>
                    <select name="platform" 
                            class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 text-lg" required>
                        <option value="instagram">📸 Instagram</option>
                        <option value="tiktok">🎵 TikTok</option>
                    </select>
                </div>

                <!-- Caption -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Caption</label>
                    <textarea name="caption" rows="6" 
                              class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 resize-y"
                              placeholder="Tulis caption menarik yang akan diposting..." required></textarea>
                </div>

                <!-- Tanggal & Waktu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal & Waktu Posting</label>
                    <input type="datetime-local" name="scheduled_at" 
                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Image (Opsional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link Gambar / Path (Opsional)</label>
                    <input type="text" name="image_path" 
                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500"
                           placeholder="https://example.com/gambar.jpg">
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <a href="{{ route('schedule.index') }}" 
                       class="flex-1 py-4 text-center border border-gray-300 dark:border-gray-600 rounded-2xl font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition shadow-lg">
                        ✅ Simpan Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection