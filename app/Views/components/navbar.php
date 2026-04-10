<nav class="fixed top-0 w-full bg-white shadow z-40" x-data="{ 
    open: false, 
    categoryOpen: false, 
    loginOpen: false,
    loginError: '' // State untuk menyimpan pesan error login dari AJAX
}">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between gap-4">

        <!-- Logo dan Kategori (Hanya untuk non-admin) -->
        <div class="flex items-center gap-4">
            <a href="/" class="text-xl font-bold text-green-600 whitespace-nowrap">
                CI4-Toko
            </a>

            <?php if (session()->get('role') !== 'admin'): ?>
                <div class="relative" @click.outside="categoryOpen = false">
                    <button @click="categoryOpen = ! categoryOpen" class="text-slate-600 font-medium hover:text-green-600 flex items-center gap-1">
                        Kategori
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Mega Menu Kategori -->
                    <div x-show="categoryOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute left-0 mt-2 w-screen max-w-4xl bg-white rounded-lg shadow-xl overflow-hidden z-50"
                        style="display: none;">
                        <div class="p-4">
                            <div class="grid grid-cols-4 gap-6">
                                <!-- Kolom Kategori 1 -->
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-3 text-sm">Elektronik</h3>
                                    <ul class="space-y-2">
                                        <li><a href="/category/electronics" class="text-sm text-gray-600 hover:text-green-600">Handphone & Tablet</a></li>
                                        <li><a href="/category/electronics" class="text-sm text-gray-600 hover:text-green-600">Laptop & Aksesoris</a></li>
                                        <li><a href="/category/electronics" class="text-sm text-gray-600 hover:text-green-600">Komputer & Aksesoris</a></li>
                                        <li><a href="/category/electronics" class="text-sm text-gray-600 hover:text-green-600">TV, Audio & Video</a></li>
                                        <li><a href="/category/electronics" class="text-sm text-gray-600 hover:text-green-600">Home Entertainment</a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Search Bar (Hanya untuk non-admin) -->
        <?php if (session()->get('role') !== 'admin'): ?>
            <div class="flex-1 max-w-2xl px-6">
                <form action="/" method="get">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Cari di CI4-Toko"
                            class="w-full rounded-lg border px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <button class="absolute right-3 top-2.5 text-slate-400 hover:text-green-600">
                            🔍
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Spacer untuk admin agar layout tetap rapi -->
            <div class="flex-1"></div>
        <?php endif; ?>

        <!-- Ikon Keranjang dan Menu Login/Profil -->
        <div class="flex items-center gap-4">
            <!-- Ikon Keranjang (Hanya untuk non-admin) -->
            <?php if (session()->get('role') !== 'admin'): ?>
                <a href="/cart" class="text-slate-600 hover:text-green-600 text-xl relative">
                    🛒
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
            <?php endif; ?>

            <?php if (! session()->get('logged_in')): ?>
                <!-- Tombol untuk membuka Modal Login -->
                <button @click="loginOpen = true; loginError = '';"
                    class="px-4 py-1.5 border border-green-600 text-green-600 rounded-lg text-sm font-medium hover:bg-green-50">
                    Masuk
                </button>
                <a href="/register" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                    Daftar
                </a>
            <?php else: ?>
                <!-- Menu Profil (saat sudah login) -->
                <div class="relative" @click.outside="open = false">
                    <button @click="open = ! open" class="flex items-center gap-2 text-slate-700 hover:text-green-600">
                        <?php $avatar = session()->get('user_avatar'); ?>

                        <div class="w-8 h-8 rounded-full overflow-hidden bg-green-100 flex items-center justify-center">
                            <?php
                            $avatar = session()->get('user_avatar');
                            $avatarUrl = null;

                            if (!empty($avatar) && strpos($avatar, 'uploads/') === 0) {
                                // ✅ Avatar lokal (via controller)
                                $avatarUrl = base_url('avatar/' . basename($avatar));
                            }
                            ?>

                            <?php if ($avatarUrl): ?>
                                <img
                                    src="<?= esc($avatarUrl) ?>"
                                    alt="avatar"
                                    class="w-full h-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                <!-- fallback jika gambar gagal load -->
                                <div class="hidden w-full h-full items-center justify-center text-green-600 font-semibold text-sm">
                                    <?= strtoupper(substr(session()->get('user_name'), 0, 1)) ?>
                                </div>

                            <?php else: ?>
                                <!-- fallback jika tidak ada avatar -->
                                <span class="text-green-600 font-semibold text-sm">
                                    <?= strtoupper(substr(session()->get('user_name'), 0, 1)) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <span class="text-sm font-medium"><?= session()->get('user_name') ?></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="absolute right-0 mt-2 bg-white text-slate-700 rounded-lg shadow-lg w-48 overflow-hidden z-50"
                        style="display: none;">
                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-medium"><?= session()->get('user_name') ?></p>
                            <p class="text-xs text-gray-500"><?= session()->get('user_email') ?></p>
                        </div>

                        <!-- Menu Beranda (Hanya untuk non-admin) -->
                        <?php if (session()->get('role') !== 'admin'): ?>
                            <a href="/" class="block px-4 py-2 text-sm hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Beranda
                            </a>


                        <?php endif; ?>

                        <a href="<?= base_url('account/setting') ?>"
                            class="block px-4 py-2 text-sm hover:bg-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Pengaturan Akun
                        </a>

                        <!-- Menu Pesanan (Hanya untuk non-admin) -->
                        <?php if (session()->get('role') !== 'admin'): ?>
                            <a href="/orders" class="block px-4 py-2 text-sm hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Pesanan Saya
                            </a>

                            <a href="/chat" class="block px-4 py-2 text-sm hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 inline mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.8-3.6A7.94 7.94 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Chat
                            </a>
                        <?php endif; ?>

                        <?php if (session()->get('role') === 'penjual') : ?>

                            <!-- MENU PENJUAL -->
                            <a href="<?= base_url('store') ?>" class="block px-4 py-2 text-sm hover:bg-slate-100"> <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7l1.664-2.496A2 2 0 016.328 4h11.344a2 2 0 011.664.504L21 7M5 7h14M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7" />
                                </svg>
                                Kelola Toko
                            </a>

                        <?php elseif (session()->get('role') === 'pembeli') : ?>

                            <!-- MENU PEMBELI -->
                            <a href="/confirm" class="block px-4 py-2 text-sm hover:bg-slate-100 text-green-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Buka Toko
                            </a>

                        <?php elseif (session()->get('role') === 'admin') : ?>

                            <!-- MENU ADMIN -->
                            <a href="/admin/confirm" class="block px-4 py-2 text-sm hover:bg-slate-100 text-red-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m2 0a2 2 0 100-4 2 2 0 000 4zm0 0a2 2 0 110 4 2 2 0 010-4z" />
                                </svg>
                                Approval Toko
                            </a>

                            <!-- Tambahan menu untuk admin -->
                            <a href="/admin/dashboard" class="block px-4 py-2 text-sm hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard Admin
                            </a>

                            <a href="/admin/status" class="block px-4 py-2 text-sm hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Kelola Pengguna
                            </a>




                        <?php endif; ?>
                        <div class="border-t">
                            <a href="/logout" class="block px-4 py-2 text-sm text-red-500 hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <!-- Modal Login -->
    <div x-show="loginOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75"
        style="display: none;">

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="loginOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">

                <!-- Tombol Close Modal -->
                <div class="absolute right-4 top-4">
                    <button @click="loginOpen = false; loginError = '';" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-bold leading-6 text-gray-900 mb-6 text-center">Masuk</h3>

                    <!-- Area untuk menampilkan error dari AJAX -->
                    <div x-show="loginError" x-transition class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-400 rounded-lg" x-text="loginError"></div>

                    <form id="modalLoginForm" action="/login" method="post" class="space-y-4">
                        <?= csrf_field() ?>

                        <!-- Hidden field untuk 'next'. Diisi oleh JavaScript -->
                        <input type="hidden" name="next" id="modalNextField" value="">

                        <div>
                            <label for="modal_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="modal_email" required
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                placeholder="nama@email.com">
                        </div>

                        <div>
                            <label for="modal_password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="modal_password" required
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>

                        <div class="pt-2 space-y-3">

                            <!-- LOGIN BIASA -->
                            <button type="submit"
                                class="flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                Masuk Sekarang
                            </button>

                            <!-- PEMISAH -->
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <div class="flex-1 border-t"></div>
                                atau
                                <div class="flex-1 border-t"></div>
                            </div>

                            <!-- LOGIN GOOGLE -->
                            <a :href="'/auth/google?next=' + encodeURIComponent(window.location.pathname + window.location.search)"
                                class="flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                                <img src="https://developers.google.com/identity/images/g-logo.png" class="w-5 h-5">
                                Masuk dengan Google
                            </a>

                        </div>

                    </form>

                    <div class="mt-6 text-center text-sm text-gray-500">
                        Belum punya akun?
                        <a href="/register" class="font-semibold text-green-600 hover:text-green-500">Daftar disini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        // Fungsi untuk menampilkan notifikasi flashdata
        function showFlashMessage(message, type = 'success') {
            const container = document.getElementById('flash-container');
            const alertDiv = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';

            alertDiv.className = `p-4 mb-4 text-sm ${bgColor} rounded-lg border relative`;
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="absolute top-2.5 right-2.5 text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        `;
            container.appendChild(alertDiv);

            // Hapus notifikasi setelah 5 detik
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Cek jika ada flashdata sukses dari server (misal setelah logout)
        <?php if (session()->getFlashdata('success')): ?>
            showFlashMessage('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>

        // Isi hidden field 'next' di modal saat halaman dimuat
        const urlParams = new URLSearchParams(window.location.search);
        const nextPath = urlParams.get('next');
        if (nextPath) {
            document.getElementById('modalNextField').value = nextPath;
        }

        // Tangani submit form modal dengan AJAX
        const modalLoginForm = document.getElementById('modalLoginForm');
        if (modalLoginForm) {
            modalLoginForm.addEventListener('submit', async (e) => {
                e.preventDefault(); // Cegah submit form biasa

                const formData = new FormData(modalLoginForm);
                const alpineData = Alpine.$data(modalLoginForm.closest('[x-data]'));

                try {
                    const response = await fetch(modalLoginForm.action, {
                        method: 'POST',
                        body: formData,
                    });

                    // Cek jika response adalah JSON (berhasil atau error dari controller)
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        const result = await response.json();

                        if (result.status === 'success') {
                            // Login berhasil, arahkan ulang halaman
                            window.location.href = result.redirect;
                        } else {
                            // Login gagal, tampilkan pesan error di modal
                            alpineData.loginError = result.message;
                        }
                    } else {
                        // Jika bukan JSON (misal error 500 atau halaman lain), redirect ke halaman login
                        window.location.href = modalLoginForm.action + '?next=' + encodeURIComponent(formData.get('next') || '/');
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    // Jika fetch gagal (network error), redirect ke halaman login
                    window.location.href = modalLoginForm.action + '?next=' + encodeURIComponent(formData.get('next') || '/');
                }
            });
        }
    });
</script>