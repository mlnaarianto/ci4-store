<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div x-data="modalHandler(<?= $profileIncomplete ? 'true' : 'false' ?>)"
    class="max-w-7xl mx-auto"> <!-- HEADER -->


    <!-- MODAL PROFILE BELUM LENGKAP -->
    <div x-show="profileModal"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        style="display:none">

        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold mb-2 text-slate-800">
                Lengkapi Profil
            </h2>

            <p class="text-sm text-slate-600 mb-4">
                Alamat dan nomor HP kamu belum lengkap.
                Silakan lengkapi profil terlebih dahulu sebelum mengajukan pembukaan toko.
            </p>

            <div class="flex justify-end">
                <a href="/account/setting"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    Lengkapi Sekarang
                </a>
            </div>
        </div>
    </div>


    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-slate-800 flex items-center">
            <svg class="w-8 h-8 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Permintaan Buka Toko
        </h1>

        <button
            @click="profileIncomplete ? profileModal = true : openTambah = true" class="px-5 py-3 bg-emerald-600 text-white rounded-xl shadow-lg hover:bg-emerald-700 transition-all duration-300 flex items-center transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Ajukan Buka Toko
        </button>
    </div>

    <!-- FILTER -->
    <div class="bg-white p-2 rounded-xl shadow-sm mb-6 inline-flex">
        <a href="/confirm" class="filter-btn px-4 py-2 rounded-lg hover:bg-slate-100 transition-all duration-200 flex items-center <?php if (!isset($_GET['status'])) echo 'bg-slate-100 font-semibold'; ?>">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            Semua
        </a>
        <a href="/confirm?status=menunggu" class="filter-btn px-4 py-2 rounded-lg hover:bg-yellow-50 transition-all duration-200 flex items-center <?php if (isset($_GET['status']) && $_GET['status'] == 'menunggu') echo 'bg-yellow-50 font-semibold text-yellow-700'; ?>">
            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
            Menunggu
        </a>
        <a href="/confirm?status=disetujui" class="filter-btn px-4 py-2 rounded-lg hover:bg-green-50 transition-all duration-200 flex items-center <?php if (isset($_GET['status']) && $_GET['status'] == 'disetujui') echo 'bg-green-50 font-semibold text-green-700'; ?>">
            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
            Disetujui
        </a>
        <a href="/confirm?status=ditolak" class="filter-btn px-4 py-2 rounded-lg hover:bg-red-50 transition-all duration-200 flex items-center <?php if (isset($_GET['status']) && $_GET['status'] == 'ditolak') echo 'bg-red-50 font-semibold text-red-700'; ?>">
            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
            Ditolak
        </a>
    </div>

    <!-- WARNING SEKALI BACA -->
    <div x-show="showInfo"
        x-transition
        class="mb-4 p-4 bg-blue-50 border border-blue-300 text-blue-800 rounded-lg flex justify-between items-start"
        style="display:none">

        <div class="pr-4">
            <p class="font-semibold mb-1">Perhatian!</p>
            <p class="text-sm">
                Pastikan nomor KTP dan foto yang diupload benar dan jelas.
                Jika data tidak sesuai, permintaan pembukaan toko akan ditolak oleh admin.
            </p>
        </div>

        <button @click="closeInfo"
            class="ml-4 text-blue-700 hover:text-blue-900 font-bold">
            ✕
        </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>



    <!-- TABLE -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No KTP</th>
                        <th class="px-6 py-4 font-semibold">Foto KTP</th>
                        <th class="px-6 py-4 font-semibold">Selfie + KTP</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Dibuat</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if (!empty($confirms)): ?>
                        <?php foreach ($confirms as $confirm): ?>
                            <tr class="hover:bg-slate-50 transition-all duration-200">
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    <?= esc($confirm['no_ktp']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($confirm['foto_ktp'])): ?>
                                        <img
                                            src="<?= base_url('confirm/image/ktp/' . $confirm['foto_ktp']) ?>"
                                            class="w-24 h-16 object-cover rounded-lg border shadow cursor-pointer hover:scale-105 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($confirm['foto_selfie_ktp'])): ?>
                                        <img
                                            src="<?= base_url('confirm/image/selfie/' . $confirm['foto_selfie_ktp']) ?>"
                                            class="w-24 h-16 object-cover rounded-lg border shadow cursor-pointer hover:scale-105 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($confirm['status'] == 'menunggu'): ?>
                                        <span class="px-3 py-1.5 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium flex items-center w-fit">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                                            Menunggu
                                        </span>
                                    <?php elseif ($confirm['status'] == 'disetujui'): ?>
                                        <span class="px-3 py-1.5 text-xs rounded-full bg-green-100 text-green-700 font-medium flex items-center w-fit">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Disetujui
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1.5 text-xs rounded-full bg-red-100 text-red-700 font-medium flex items-center w-fit">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Ditolak
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <?= date('d M Y H:i', strtotime($confirm['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <?php if ($confirm['status'] == 'menunggu'): ?>
                                            <button
                                                @click="openEdit(<?= $confirm['id'] ?>, '<?= $confirm['no_ktp'] ?>')"
                                                class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs hover:bg-blue-600 transition-all duration-200 flex items-center transform hover:scale-105">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>

                                            <form action="/confirm/delete/<?= $confirm['id'] ?>" method="post"
                                                onsubmit="return confirm('Yakin hapus?')" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Belum ada data permintaan</p>
                                    <p class="text-sm mt-1">Klik tombol "Ajukan Buka Toko" untuk menambah permintaan baru</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div x-show="openTambah"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openTambah"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="openTambah = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openTambah"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajukan Buka Toko
                        </h3>
                        <button @click="openTambah = false" class="text-white hover:text-emerald-100 focus:outline-none transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="/confirm/store" method="post" enctype="multipart/form-data" class="px-6 pt-5 pb-4">
                    <?= csrf_field() ?>

                    <div class="mb-5">
                        <label for="no_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor KTP
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <input type="text" id="no_ktp" name="no_ktp" placeholder="Masukkan nomor KTP"
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto KTP
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="foto_ktp" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau GIF (MAX. 2MB)</p>
                                </div>
                                <input id="foto_ktp" name="foto_ktp" type="file" class="hidden" required />
                            </label>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="foto_selfie_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Selfie + KTP
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="foto_selfie_ktp" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau GIF (MAX. 2MB)</p>
                                </div>
                                <input id="foto_selfie_ktp" name="foto_selfie_ktp" type="file" class="hidden" required />
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button"
                            @click="openTambah = false"
                            class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div x-show="openEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openEditModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="openEditModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openEditModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Permintaan
                        </h3>
                        <button @click="openEditModal = false" class="text-white hover:text-blue-100 focus:outline-none transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form :action="'/confirm/update/' + editId"
                    method="post"
                    enctype="multipart/form-data"
                    class="px-6 pt-5 pb-4">
                    <?= csrf_field() ?>

                    <div class="mb-5">
                        <label for="edit_no_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor KTP
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <input type="text" id="edit_no_ktp" name="no_ktp" x-model="editKtp"
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="edit_foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto KTP
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="edit_foto_ktp" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau GIF (MAX. 2MB)</p>
                                </div>
                                <input id="edit_foto_ktp" name="foto_ktp" type="file" class="hidden" />
                            </label>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="edit_foto_selfie_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Selfie + KTP
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="edit_foto_selfie_ktp" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau GIF (MAX. 2MB)</p>
                                </div>
                                <input id="edit_foto_selfie_ktp" name="foto_selfie_ktp" type="file" class="hidden" />
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button"
                            @click="openEditModal = false"
                            class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= IMAGE MODAL ================= -->
    <div x-show="imageModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="imageModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"
                @click="imageModal = false; imageSrc = ''"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="imageModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Preview Gambar</h3>
                        <button @click="imageModal = false; imageSrc = ''" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <img :src="imageSrc" class="w-full h-auto rounded-lg" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <!-- ================= NOTIFICATION ================= -->
    <div x-show="notification.show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-4 right-4 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden z-50"
        style="display: none;">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div x-show="notification.type == 'success'" class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div x-show="notification.type == 'error'" class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p x-show="notification.type == 'success'" class="text-sm font-medium text-gray-900">Berhasil</p>
                    <p x-show="notification.type == 'error'" class="text-sm font-medium text-gray-900">Error</p>
                    <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="notification.show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= ALPINE SCRIPT ================= -->
<script>
    function modalHandler(profileIncomplete = false) {
        return {
            openTambah: false,
            openEditModal: false,
            imageModal: false,
            profileModal: profileIncomplete,
            profileIncomplete: profileIncomplete,
            imageSrc: '',
            editId: null,
            editKtp: '',
            showInfo: true,
            notification: {
                show: false,
                type: 'success',
                message: ''
            },

            openEdit(id, ktp) {
                this.editId = id;
                this.editKtp = ktp;
                this.openEditModal = true;
            },

            showImageModal(src) {
                this.imageSrc = src;
                this.imageModal = true;
            },

            showNotification(message, type = 'success') {
                this.notification.message = message;
                this.notification.type = type;
                this.notification.show = true;

                setTimeout(() => {
                    this.notification.show = false;
                }, 3000);
            },

            closeInfo() {
                this.showInfo = false;
                localStorage.setItem('confirm_warning_read', '1');
            },
        }
    }


    // cek apakah user sudah pernah baca warning
    document.addEventListener('alpine:init', () => {
        const read = localStorage.getItem('confirm_warning_read');

        if (read === '1') {
            setTimeout(() => {
                const root = document.querySelector('[x-data]');
                if (root && root.__x) {
                    root.__x.$data.showInfo = false;
                }
            }, 50);
        }
    });
    // File upload preview
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');

        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const label = this.closest('label');
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);

                    // Update label content
                    const pElement = label.querySelector('p');
                    if (pElement) {
                        pElement.innerHTML = `<span class="font-semibold">${fileName}</span> (${fileSize} MB)`;
                    }

                    // Add success visual feedback
                    label.classList.add('border-emerald-500', 'bg-emerald-50');
                    label.classList.remove('border-gray-300', 'bg-gray-50');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>