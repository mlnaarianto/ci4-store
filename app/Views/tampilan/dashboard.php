<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<!-- <h1 class="text-2xl font-bold mb-6">Dashboard</h1> -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-slate-400">Total Produk</p>
        <h2 class="text-3xl font-bold mt-2">120</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-slate-400">Total Penjualan</p>
        <h2 class="text-3xl font-bold mt-2">Rp 12.500.000</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-slate-400">Pengguna Aktif</p>
        <h2 class="text-3xl font-bold mt-2">25</h2>
    </div>
</div>

<?= $this->endSection() ?>
