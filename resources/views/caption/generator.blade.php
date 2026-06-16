@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">
            ✨ Caption Generator
        </h1>

        <form id="captionForm" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Produk / Brand
                    </label>
                    <input type="text" name="product_name" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500" 
                           placeholder="Contoh: Sepatu Nike Air" required>
                </div>

                <!-- Platform -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Platform
                    </label>
                    <select name="platform" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                    </select>
                </div>
            </div>

            <!-- Keywords -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Keyword / Deskripsi Produk
                </label>
                <textarea name="keywords" rows="4"
                          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                          placeholder="Contoh: sepatu running, nyaman, waterproof, warna hitam" required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tone / Gaya
                    </label>
                    <select name="tone" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                        <option value="lucu">Lucu / Viral</option>
                        <option value="formal">Formal / Profesional</option>
                        <option value="estetik">Estetik / Aesthetic</option>
                    </select>
                </div>

                <!-- Bahasa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Bahasa
                    </label>
                    <select name="language" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3.5 rounded-xl hover:from-blue-700 hover:to-purple-700 transition">
                        🚀 Generate Caption
                    </button>
                </div>
            </div>
        </form>

        <!-- Hasil Caption -->
        <div id="result" class="mt-10 hidden"></div>
    </div>
</div>

<script>
document.getElementById('captionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('button');
    const originalText = button.innerHTML;
    button.innerHTML = '⏳ Sedang generate...';
    button.disabled = true;

    const formData = new FormData(this);

    try {
        const response = await fetch('/caption/generate', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        const resultDiv = document.getElementById('result');
        
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">✅ Hasil Caption:</h3>
                    <pre class="whitespace-pre-wrap text-gray-700 dark:text-gray-300 leading-relaxed">${data.caption}</pre>
                    <button onclick="copyAll(this)" 
                            class="mt-6 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        📋 Copy Semua Caption
                    </button>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `<div class="text-red-500">${data.message}</div>`;
        }
        
        resultDiv.classList.remove('hidden');
    } catch (error) {
        alert('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
});

function copyAll(btn) {
    const text = btn.parentElement.querySelector('pre').innerText;
    navigator.clipboard.writeText(text).then(() => {
        btn.textContent = '✅ Berhasil Disalin!';
        setTimeout(() => btn.textContent = '📋 Copy Semua Caption', 2000);
    });
}
</script>
@endsection