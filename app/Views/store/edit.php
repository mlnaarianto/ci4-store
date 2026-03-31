<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<?php
// Helper function untuk cache busting
function getCacheBuster($store, $prefix = '') {
    $timestamp = !empty($store['updated_at']) 
        ? strtotime($store['updated_at']) 
        : time();
    
    // Tambahkan random string untuk memastikan cache benar-benar baru
    return $timestamp . '_' . uniqid() . ($prefix ? '_' . $prefix : '');
}

$cacheBuster = getCacheBuster($store);
?>

<div class="max-w-4xl mx-auto py-8 px-4">
    
    <!-- Progress Status -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-600">
            <a href="<?= base_url('store') ?>" class="hover:text-blue-600 transition">Toko Saya</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-slate-400">Edit Toko</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header dengan Status -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">
                        Edit Toko
                    </h2>
                    <p class="text-sm text-slate-600 mt-1">
                        Perbarui informasi toko Anda
                    </p>
                </div>
                
                <!-- Status Badge -->
                <?php if (isset($store['status'])): ?>
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    <?= $store['status'] == 'approved' 
                        ? 'bg-green-100 text-green-700 border border-green-200' 
                        : 'bg-yellow-100 text-yellow-700 border border-yellow-200' ?>">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full 
                            <?= $store['status'] == 'approved' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                        </span>
                        Status: <?= ucfirst($store['status']) ?>
                    </span>
                </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mx-8 mt-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm"><?= session()->getFlashdata('error') ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mx-8 mt-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm"><?= session()->getFlashdata('success') ?></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?= base_url('store/update/' . $store['id']) ?>"
              method="post"
              enctype="multipart/form-data"
              class="p-8 space-y-8"
              id="editStoreForm">

            <?= csrf_field() ?>

            <!-- Informasi Dasar -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-slate-700 border-b pb-2">
                    Informasi Dasar
                </h3>

                <!-- Nama Toko -->
                <div>
                    <label for="nama_toko" class="block text-sm font-semibold mb-2 text-slate-700">
                        Nama Toko <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_toko"
                           name="nama_toko"
                           value="<?= old('nama_toko', $store['nama_toko']) ?>"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Masukkan nama toko"
                           required>
                    <p class="text-xs text-slate-400 mt-1">
                        Minimal 3 karakter, maksimal 150 karakter
                    </p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold mb-2 text-slate-700">
                        Deskripsi Toko
                    </label>
                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="5"
                              class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Ceritakan tentang toko Anda..."><?= old('deskripsi', $store['deskripsi']) ?></textarea>
                    <p class="text-xs text-slate-400 mt-1">
                        Deskripsi yang baik akan membantu pelanggan mengenal toko Anda
                    </p>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-semibold mb-2 text-slate-700">
                        Alamat Toko
                    </label>
                    <input type="text"
                           id="alamat"
                           name="alamat"
                           value="<?= old('alamat', $store['alamat']) ?>"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Masukkan alamat lengkap">
                </div>
            </div>

            <!-- Logo Toko -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-700 border-b pb-2">
                    Logo Toko
                </h3>

                <div class="bg-slate-50 rounded-xl p-6">
                    <!-- Preview Logo -->
                    <?php if (!empty($store['logo'])): ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-600 mb-3">
                            Logo Saat Ini:
                        </label>
                        <div class="relative inline-block">
                            <img src="<?= base_url('store/image/logo/' . $store['logo']) ?>?v=<?= $cacheBuster ?>"
                                 class="w-32 h-32 object-cover rounded-xl border-4 border-white shadow-lg"
                                 alt="Logo Toko"
                                 id="logoPreview"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=Logo+Not+Found'; this.classList.add('border-red-200');">
                            <button type="button"
                                    onclick="refreshImage('logo', '<?= $store['logo'] ?>')"
                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1.5 shadow-md hover:bg-slate-100 transition"
                                    title="Refresh gambar">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Upload Logo Baru -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">
                            <?= !empty($store['logo']) ? 'Ganti Logo' : 'Upload Logo' ?>
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <input type="file"
                                       id="logo"
                                       name="logo"
                                       accept="image/png, image/jpeg, image/jpg"
                                       class="block w-full text-sm text-slate-500
                                              file:mr-4 file:py-2.5 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100
                                              transition cursor-pointer">
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-2 text-xs text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Format: JPG, JPEG, PNG. Maks: 2MB. Kosongkan jika tidak ingin mengganti.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Toko -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-700 border-b pb-2">
                    Banner Toko
                </h3>

                <div class="bg-slate-50 rounded-xl p-6">
                    <!-- Preview Banner -->
                    <?php if (!empty($store['banner'])): ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-600 mb-3">
                            Banner Saat Ini:
                        </label>
                        <div class="relative">
                            <img src="<?= base_url('store/image/banner/' . $store['banner']) ?>?v=<?= $cacheBuster ?>"
                                 class="w-full h-48 object-cover rounded-xl border-4 border-white shadow-lg"
                                 alt="Banner Toko"
                                 id="bannerPreview"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/1200x300?text=Banner+Not+Found'; this.classList.add('border-red-200');">
                            <button type="button"
                                    onclick="refreshImage('banner', '<?= $store['banner'] ?>')"
                                    class="absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:bg-slate-100 transition"
                                    title="Refresh banner">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Upload Banner Baru -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">
                            <?= !empty($store['banner']) ? 'Ganti Banner' : 'Upload Banner' ?>
                        </label>
                        <input type="file"
                               id="banner"
                               name="banner"
                               accept="image/png, image/jpeg, image/jpg"
                               class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2.5 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100
                                      transition cursor-pointer">
                        <div class="mt-3 flex items-center gap-2 text-xs text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Format: JPG, JPEG, PNG. Maks: 4MB. Banner optimal: 1200 x 300 px. Kosongkan jika tidak ingin mengganti.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Image Upload -->
            <div class="bg-blue-50 rounded-xl p-4 hidden" id="previewContainer">
                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Preview Gambar Baru
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div id="logoPreviewNew" class="hidden">
                        <p class="text-xs text-blue-600 mb-1">Logo Baru:</p>
                        <img src="" class="w-24 h-24 object-cover rounded-lg border-2 border-blue-200">
                    </div>
                    <div id="bannerPreviewNew" class="hidden">
                        <p class="text-xs text-blue-600 mb-1">Banner Baru:</p>
                        <img src="" class="w-full h-32 object-cover rounded-lg border-2 border-blue-200">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('store') ?>" 
                       class="px-6 py-2.5 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 transition text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    
                    <button type="button"
                            onclick="window.location.reload()"
                            class="px-6 py-2.5 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 transition text-sm font-medium">
                        Reset
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            id="submitBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg text-sm font-medium transition inline-flex items-center gap-2 shadow-lg shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Preview dan Refresh -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview logo sebelum upload
    document.getElementById('logo').addEventListener('change', function(e) {
        previewImage(e, 'logoPreviewNew', 'logo');
    });
    
    // Preview banner sebelum upload
    document.getElementById('banner').addEventListener('change', function(e) {
        previewImage(e, 'bannerPreviewNew', 'banner');
    });
});

function previewImage(event, previewId, type) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('previewContainer');
            const previewElement = document.getElementById(previewId);
            const img = previewElement.querySelector('img');
            
            img.src = e.target.result;
            previewElement.classList.remove('hidden');
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function refreshImage(type, filename) {
    // Tampilkan loading
    const button = event.currentTarget;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    button.disabled = true;
    
    // Generate URL dengan cache buster baru
    const baseUrl = '<?= base_url() ?>';
    const timestamp = new Date().getTime();
    const random = Math.random().toString(36).substring(7);
    const newUrl = `${baseUrl}store/image/${type}/${filename}?v=${timestamp}_${random}`;
    
    // Update gambar
    const imgElement = type === 'logo' ? 
        document.getElementById('logoPreview') : 
        document.getElementById('bannerPreview');
    
    if (imgElement) {
        // Preload gambar baru
        const newImg = new Image();
        newImg.onload = function() {
            imgElement.src = newUrl;
            
            // Kembalikan tombol ke normal
            button.innerHTML = originalHtml;
            button.disabled = false;
            
            // Tampilkan notifikasi sukses
            showNotification('Gambar berhasil direfresh!', 'success');
        };
        newImg.onerror = function() {
            button.innerHTML = originalHtml;
            button.disabled = false;
            showNotification('Gagal refresh gambar', 'error');
        };
        newImg.src = newUrl;
    }
}

function showNotification(message, type) {
    // Buat element notifikasi
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-sm font-medium z-50 animate-fade-in ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = message;
    
    document.body.appendChild(notification);
    
    // Hapus setelah 3 detik
    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Konfirmasi sebelum submit jika ada perubahan gambar
document.getElementById('editStoreForm').addEventListener('submit', function(e) {
    const logoFile = document.getElementById('logo').files[0];
    const bannerFile = document.getElementById('banner').files[0];
    
    if (logoFile || bannerFile) {
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan? Gambar lama akan diganti.')) {
            e.preventDefault();
        }
    }
});

// Tambahkan CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    .animate-fade-out {
        animation: fadeOut 0.3s ease-out;
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

<?= $this->endSection() ?>