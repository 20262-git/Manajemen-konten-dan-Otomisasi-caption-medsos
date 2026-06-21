@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-10">

        <div class="text-center mb-12">
            <h3 class="text-4xl font-bold text-gray-800 dark:text-white mb-3">✨ Caption Generator</h3>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                Buat caption menarik & viral untuk Instagram dan TikTok
            </p>
        </div>

        <form id="captionForm" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Produk / Brand</label>
                    <input type="text" name="product_name" 
                           class="w-full px-6 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Sepatu Nike Air" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Platform</label>
                    <select name="platform" class="w-full px-6 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500" required>
                        <option value="instagram">📸 Instagram</option>
                        <option value="tiktok">🎵 TikTok</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Keyword / Deskripsi Produk</label>
                <textarea name="keywords" rows="4" 
                          class="w-full px-6 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500"
                          placeholder="Contoh: sepatu running, nyaman, waterproof, warna hitam" required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tone / Gaya</label>
                    <select name="tone" class="w-full px-6 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500" required>
                        <option value="lucu">😄 Lucu / Viral</option>
                        <option value="formal">💼 Formal / Profesional</option>
                        <option value="estetik">🌸 Estetik / Aesthetic</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bahasa</label>
                    <select name="language" class="w-full px-6 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500" required>
                        <option value="id">🇮🇩 Bahasa Indonesia</option>
                        <option value="en">🇬🇧 English</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-4 rounded-2xl text-lg shadow-lg hover:shadow-xl transition">
                        🚀 Generate Caption
                    </button>
                </div>
            </div>
        </form>

        <!-- Hasil Caption -->
<div id="result" class="mt-12 hidden"></div>
    </div>
</div>

<script>
document.getElementById('captionForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const btn = e.target.querySelector('button');
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '⏳ Sedang membuat caption...';

    const formData = new FormData(e.target);

    try {
        const res = await fetch('/caption/generate', { 
            method: 'POST', 
            body: formData 
        });
        const data = await res.json();

        const result = document.getElementById('result');
        
        if (data.success) {
            result.innerHTML = `
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-8">
                    <h3 class="font-semibold text-2xl mb-6">✅ Hasil Caption</h3>
                    <pre class="whitespace-pre-wrap text-gray-700 dark:text-gray-300 leading-relaxed text-lg mb-8">${data.caption}</pre>
                    
                    <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-2xl">
                        <h4 class="font-medium mb-4">Simpan ke Jadwal Posting</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="datetime-local" id="scheduled_at" 
                                   class="w-full px-5 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl">
                            <button onclick="saveToSchedule('${data.caption.replace(/'/g, "\\'")}')" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-2xl">
                                💾 Simpan ke Jadwal
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            result.innerHTML = `<div class="text-red-500 p-4">${data.message}</div>`;
        }
        
        result.classList.remove('hidden');
    } catch (err) {
        alert('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = original;
    }
});

async function saveToSchedule(caption) {
    const scheduledAt = document.getElementById('scheduled_at').value;
    if (!scheduledAt) {
        alert('Silakan pilih tanggal & waktu posting');
        return;
    }

    const platform = document.querySelector('select[name="platform"]').value;

    try {
        const res = await fetch('/caption/save-to-schedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                caption: caption,
                platform: platform,
                scheduled_at: scheduledAt
            })
        });

        const data = await res.json();
        if (data.success) {
            alert(data.message);
        } else {
            alert('Gagal menyimpan jadwal');
        }
    } catch (err) {
        alert('Terjadi kesalahan');
    }
}
</script>
@endsection