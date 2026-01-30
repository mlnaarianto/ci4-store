<!-- app/Views/components/dashboard-cards.php -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">

    <!-- 🔒 CARD TERKUNCI (PAKSA LOGIN) -->
    <a href="/orders"
       class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition block">
        <div class="text-3xl mb-2">📦</div>
        <h3 class="text-lg font-semibold">Total Pesanan</h3>
        <p class="text-2xl font-bold text-green-600">0</p>
    </a>

    <!-- CARD BEBAS -->
    <div class="bg-white p-6 rounded-lg shadow">
    <div class="text-3xl mb-2">💳</div>
        <h3 class="text-lg font-semibold">Metode Pembayaran</h3>
        <p class="text-sm text-gray-600">Belum ada</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="text-3xl mb-2">📍</div>
        <h3 class="text-lg font-semibold">Alamat Pengiriman</h3>
        <p class="text-sm text-gray-600">Belum diatur</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="text-3xl mb-2">👤</div>
        <h3 class="text-lg font-semibold">Profil</h3>
        <p class="text-sm text-gray-600">Lengkap 80%</p>
    </div>

</div>
