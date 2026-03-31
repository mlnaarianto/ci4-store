<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto py-6 px-4">

    <?php if (!$store): ?>

        <!-- ================= BELUM PUNYA TOKO ================= -->
        <div class="bg-white rounded-2xl shadow p-10 text-center">
            <h2 class="text-2xl font-bold mb-3">
                Kamu belum memiliki toko
            </h2>
            <p class="text-slate-500 mb-6">
                Yuk buat toko sekarang dan mulai berjualan 🚀
            </p>
            <a href="<?= base_url('store/create') ?>"
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm transition">
                + Buat Toko
            </a>
        </div>

    <?php else: ?>

        <!-- ================= BANNER DENGAN CACHE BUSTING ================= -->
        <?php if (!empty($store['banner'])): ?>
        <div class="mb-6 relative group">
            <?php 
                // Generate cache buster yang unik
                $bannerVersion = isset($store['updated_at']) 
                    ? strtotime($store['updated_at']) 
                    : time();
                $bannerVersion .= '_' . uniqid(); // Tambahkan unique ID
                $bannerUrl = base_url('store/image/banner/' . $store['banner']) . '?v=' . $bannerVersion;
            ?>
            <img src="<?= $bannerUrl ?>"
                 class="w-full h-52 object-cover rounded-2xl shadow-lg"
                 alt="Banner Toko"
                 id="bannerImage"
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/1200x300?text=Banner+Tidak+Ditemukan'; this.classList.add('border-2', 'border-red-200');">
            
            <!-- Tombol Refresh Banner -->
            <button onclick="refreshImage('banner', '<?= $store['banner'] ?>', '<?= $bannerVersion ?>')"
                    class="absolute top-3 right-3 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition hover:bg-slate-100"
                    title="Refresh banner">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
        <?php endif; ?>

        <!-- ================= STORE HEADER ================= -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <!-- Logo + Info -->
                <div class="flex items-center gap-5">

                    <?php if (!empty($store['logo'])): ?>
                        <?php 
                            $logoVersion = isset($store['updated_at']) 
                                ? strtotime($store['updated_at']) 
                                : time();
                            $logoVersion .= '_' . uniqid();
                            $logoUrl = base_url('store/image/logo/' . $store['logo']) . '?v=' . $logoVersion;
                        ?>
                        <div class="relative group">
                            <img src="<?= $logoUrl ?>"
                                 class="w-20 h-20 rounded-xl object-cover border-2 border-white shadow-md"
                                 alt="Logo Toko"
                                 id="logoImage"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=Logo+Error'; this.classList.add('border-red-200');">
                            
                            <!-- Tombol Refresh Logo -->
                            <button onclick="refreshImage('logo', '<?= $store['logo'] ?>', '<?= $logoVersion ?>')"
                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1.5 shadow-md opacity-0 group-hover:opacity-100 transition hover:bg-slate-100"
                                    title="Refresh logo">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                    <?php else: ?>
                        <img src="https://via.placeholder.com/150?text=No+Logo"
                             class="w-20 h-20 rounded-xl object-cover border-2 border-slate-200"
                             alt="Placeholder">
                    <?php endif; ?>

                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">
                            <?= esc($store['nama_toko']) ?>
                        </h1>

                        <div class="mt-2 text-sm text-slate-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?= esc($store['alamat'] ?? 'Alamat belum diisi') ?>
                        </div>

                        <div class="mt-3 flex gap-2">
                            <span class="px-3 py-1 text-xs rounded-full flex items-center gap-1
                                <?= $store['status'] == 'approved'
                                    ? 'bg-green-100 text-green-700 border border-green-200'
                                    : 'bg-yellow-100 text-yellow-700 border border-yellow-200' ?>">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    <?= $store['status'] == 'approved' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                                </span>
                                <?= ucfirst($store['status']) ?>
                            </span>
                            
                            <?php if (!empty($store['updated_at'])): ?>
                            <span class="px-3 py-1 text-xs rounded-full bg-slate-100 text-slate-600">
                                Diperbarui: <?= date('d M Y', strtotime($store['updated_at'])) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <!-- ACTION BUTTON -->
                <div>
                    <a href="<?= base_url('store/edit/' . $store['id']) ?>"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition inline-flex items-center gap-2 shadow-md shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Toko
                    </a>
                </div>

            </div>
        </div>

        <!-- ================= DESKRIPSI ================= -->
        <div class="bg-white rounded-2xl shadow p-6 mb-8">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2 text-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                Deskripsi Toko
            </h2>
            <p class="text-slate-600 text-sm leading-relaxed">
                <?= esc($store['deskripsi'] ?? 'Belum ada deskripsi.') ?>
            </p>
        </div>

        <!-- ================= PRODUCT GRID ================= -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Produk di Toko Kamu
            </h2>

            <a href="<?= base_url('product/create') ?>"
               class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk
            </a>
        </div>

        <?php if (!empty($products)): ?>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">

                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden group">

                    <div class="relative">
                        <img src="<?= !empty($product['gambar'])
                                ? base_url('product/image/' . $product['gambar']) . '?v=' . time()
                                : 'https://via.placeholder.com/300?text=No+Image' ?>"
                             class="w-full h-48 object-contain bg-white p-4"
                             alt="<?= esc($product['nama_produk']) ?>"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/300?text=Error';">
                        
                        <?php if ($product['stok'] <= 0): ?>
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            Habis
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="p-4">
                        <h3 class="text-sm font-semibold line-clamp-2 group-hover:text-green-600 transition">
                            <?= esc($product['nama_produk']) ?>
                        </h3>

                        <div class="mt-2">
                            <p class="text-red-600 font-bold text-sm">
                                Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                            </p>
                        </div>

                        <div class="flex justify-between items-center mt-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Stok: <?= esc($product['stok']) ?>
                            </span>
                            <a href="<?= base_url('product/edit/' . $product['id']) ?>"
                               class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="bg-white rounded-2xl shadow p-10 text-center text-slate-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-lg">Belum ada produk di toko kamu.</p>
                <p class="text-sm mt-2">Yuk tambahkan produk pertama Anda!</p>
            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>

<!-- JavaScript untuk Refresh Gambar -->
<script>
function refreshImage(type, filename, oldVersion) {
    // Tampilkan loading pada tombol
    const button = event.currentTarget;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    button.disabled = true;
    
    // Generate version baru
    const timestamp = new Date().getTime();
    const random = Math.random().toString(36).substring(7);
    const newVersion = timestamp + '_' + random;
    
    // Dapatkan elemen gambar
    const imgId = type === 'logo' ? 'logoImage' : 'bannerImage';
    const imgElement = document.getElementById(imgId);
    
    if (imgElement) {
        // Buat URL baru
        const baseUrl = '<?= base_url() ?>';
        const newUrl = `${baseUrl}store/image/${type}/${filename}?v=${newVersion}`;
        
        // Preload gambar baru
        const newImg = new Image();
        newImg.onload = function() {
            imgElement.src = newUrl;
            
            // Kembalikan tombol ke normal
            button.innerHTML = originalHtml;
            button.disabled = false;
            
            // Tampilkan notifikasi
            showNotification('Gambar berhasil diperbarui!', 'success');
        };
        
        newImg.onerror = function() {
            button.innerHTML = originalHtml;
            button.disabled = false;
            showNotification('Gagal memuat gambar', 'error');
        };
        
        newImg.src = newUrl;
    }
}

function showNotification(message, type) {
    // Buat elemen notifikasi
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

// Tambahkan CSS untuk animasi
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
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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
`;
document.head.appendChild(style);
</script>

<?= $this->endSection() ?>