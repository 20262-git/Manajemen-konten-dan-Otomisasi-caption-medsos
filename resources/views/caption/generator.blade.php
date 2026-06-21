@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-8 md:p-10">
        <h1 class="text-4xl font-bold text-center mb-2 text-gray-800 dark:text-white">
            ✨ Caption Generator
        </h1>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-10">
            Gunakan Gemini AI untuk membuat caption menarik
        </p>

        <form id="captionForm" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Produk / Brand</label>
                    <input type="text" name="product_name" 
                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" 
                           placeholder="Contoh: Sepatu Nike Air" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Platform</label>
                    <select name="platform" class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" required>
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keyword / Deskripsi Produk</label>
                <textarea name="keywords" rows="4" 
                          class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 dark:bg-gray-700"
                          placeholder="Contoh: sepatu running, nyaman, waterproof, warna hitam" required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tone / Gaya</label>
                    <select name="tone" class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" required>
                        <option value="lucu">Lucu / Viral</option>
                        <option value="formal">Formal / Profesional</option>
                        <option value="estetik">Estetik / Aesthetic</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bahasa</label>
                    <select name="language" class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 dark:bg-gray-700" required>
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 rounded-2xl transition">
                        🚀 Generate Caption
                    </button>
                </div>
            </div>
        </form>

        <div id="result" class="mt-10 hidden"></div>
    </div>
</div>

<script>
document.getElementById('captionForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    const original = btn.innerHTML;
    btn.innerHTML = '⏳ Generating...';
    btn.disabled = true;

    const formData = new FormData(e.target);

    try {
        const res = await fetch('/caption/generate', { method: 'POST', body: formData });
        const data = await res.json();

        document.getElementById('result').innerHTML = `
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-8">
                <h3 class="font-semibold text-xl mb-4">✅ Hasil Caption</h3>
                <pre class="whitespace-pre-wrap text-gray-700 dark:text-gray-300 leading-relaxed text-lg">${data.caption || data.message}</pre>
            </div>
        `;
        document.getElementById('result').classList.remove('hidden');
    } catch (err) {
        alert('Terjadi kesalahan');
    } finally {
        btn.innerHTML = original;
        btn.disabled = false;
    }
});
</script>
@endsection