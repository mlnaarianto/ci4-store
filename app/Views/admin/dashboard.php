<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto py-10">

    <!-- Header -->
    <div class="mb-10 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Dashboard Admin
            </h1>
            <p class="text-slate-500 mt-2">
                Selamat datang kembali, <?= esc($user) ?> 👋
            </p>
        </div>
        <div
            x-data="clock()"
            x-init="start()"
            class="text-sm text-slate-400 font-mono">
            <span x-text="date"></span>
            &nbsp;&nbsp;
            <span x-text="time"></span>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition duration-300 p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <h2 class="text-sm text-slate-500">Pembeli</h2>
                <span class="text-blue-500 text-xl">🧑‍💼</span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-3">
                <?= number_format($totalPembeli) ?>
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition duration-300 p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-center">
                <h2 class="text-sm text-slate-500">Penjual</h2>
                <span class="text-indigo-500 text-xl">🏪</span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-3">
                <?= number_format($totalPenjual) ?>
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition duration-300 p-6 border-l-4 border-emerald-500">
            <div class="flex justify-between items-center">
                <h2 class="text-sm text-slate-500">User Terdaftar</h2>
                <span class="text-emerald-500 text-xl">👤</span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-3">
                <?= number_format($totalUsers) ?>
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition duration-300 p-6 border-l-4 border-green-600">
            <div class="flex justify-between items-center">
                <h2 class="text-sm text-slate-500">Pendapatan</h2>
                <span class="text-green-600 text-xl">💰</span>
            </div>
            <p class="text-3xl font-bold text-green-600 mt-3">Rp 12.500.000</p>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm p-8 mb-10">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Aksi Cepat</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <a href="/admin/stores"
                class="flex items-center justify-center gap-2 bg-purple-500 hover:bg-purple-600 text-white py-3 rounded-xl transition duration-300 shadow hover:shadow-lg">

                🏪 Approval Toko

            </a>

            <a href="/admin/pesanan"
                class="flex items-center justify-center gap-2 bg-indigo-500 hover:bg-indigo-600 text-white py-3 rounded-xl transition duration-300 shadow hover:shadow-lg">
                🛒 Kelola Pesanan
            </a>

            <a href="/admin/user"
                class="flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white py-3 rounded-xl transition duration-300 shadow hover:shadow-lg">
                👤 Kelola User
            </a>

        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Aktivitas Terbaru</h2>

        <ul class="space-y-4 text-sm text-slate-600">
            <li class="flex justify-between border-b pb-2">
                <span>Pesanan baru dari Andi</span>
                <span class="text-slate-400">5 menit lalu</span>
            </li>
            <li class="flex justify-between border-b pb-2">
                <span>User baru mendaftar</span>
                <span class="text-slate-400">1 jam lalu</span>
            </li>
            <li class="flex justify-between">
                <span>Produk baru ditambahkan</span>
                <span class="text-slate-400">Kemarin</span>
            </li>
        </ul>
    </div>

</div>


<script>
    function clock() {
        return {
            date: '',
            time: '',
            start() {
                this.update()
                setInterval(() => this.update(), 1000)
            },
            update() {
                const now = new Date()

                // Format Indonesia: Kam, 26 Feb 2026
                this.date = now.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                })

                // Format jam: 02 : 03 : 21
                const h = String(now.getHours()).padStart(2, '0')
                const m = String(now.getMinutes()).padStart(2, '0')
                const s = String(now.getSeconds()).padStart(2, '0')
                this.time = `${h} : ${m} : ${s}`
            }
        }
    }
</script>
<?= $this->endSection() ?>