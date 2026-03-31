<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-slate-800">
            Approval Permintaan Buka Toko
        </h1>
    </div>

    <!-- FILTER -->

    <!-- FILTER -->
    <div class="bg-white p-2 rounded-xl shadow-sm mb-6 inline-flex">
        <a href="/admin/confirm" class="filter-btn px-4 py-2 rounded-lg hover:bg-slate-100 transition-all duration-200 flex items-center <?php if (!isset($_GET['status'])) echo 'bg-slate-100 font-semibold'; ?>">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            Semua
        </a>
        <a href="/admin/confirm?status=menunggu" class="filter-btn px-4 py-2 rounded-lg hover:bg-yellow-50 transition-all duration-200 flex items-center <?php if (isset($_GET['status']) && $_GET['status'] == 'menunggu') echo 'bg-yellow-50 font-semibold text-yellow-700'; ?>">
            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
            Menunggu
        </a>
        <a href="/admin/confirm?status=disetujui" class="filter-btn px-4 py-2 rounded-lg hover:bg-green-50 transition-all duration-200 flex items-center <?php if (isset($_GET['status']) && $_GET['status'] == 'disetujui') echo 'bg-green-50 font-semibold text-green-700'; ?>">
            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
            Disetujui
        </a>
        <a href="/admin/confirm?status=ditolak" class="filter-btn px-4 py-2 rounded-lg hover:bg-red-50 transition-all duration-200 flex items-center <?php if (isset($_GET['status']) && $_GET['status'] == 'ditolak') echo 'bg-red-50 font-semibold text-red-700'; ?>">
            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
            Ditolak
        </a>
    </div>

    <!-- FLASHDATA -->
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
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">User ID</th>
                        <th class="px-6 py-4">No KTP</th>
                        <th class="px-6 py-4">Foto KTP</th>
                        <th class="px-6 py-4">Selfie + KTP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (!empty($confirms)): ?>
                        <?php foreach ($confirms as $confirm): ?>
                            <tr>
                                <td class="px-6 py-4"><?= $confirm['user_id'] ?></td>
                                <td class="px-6 py-4"><?= esc($confirm['no_ktp']) ?></td>
                                <!-- FOTO KTP -->
                                <td class="px-6 py-4">
                                    <?php if (!empty($confirm['foto_ktp'])): ?>
                                        <img
                                            src="<?= site_url('confirm/image/ktp/' . $confirm['foto_ktp']) ?>"
                                            class="w-24 h-16 object-cover rounded-lg border shadow cursor-pointer hover:scale-105 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">Tidak ada</span>
                                    <?php endif; ?>
                                </td>

                                <!-- SELFIE -->
                                <td class="px-6 py-4">
                                    <?php if (!empty($confirm['foto_selfie_ktp'])): ?>
                                        <img
                                            src="<?= site_url('confirm/image/selfie/' . $confirm['foto_selfie_ktp']) ?>"
                                            class="w-24 h-16 object-cover rounded-lg border shadow cursor-pointer hover:scale-105 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs
                                        <?= $confirm['status'] == 'menunggu' ? 'bg-yellow-100 text-yellow-700' : '' ?>
                                        <?= $confirm['status'] == 'disetujui' ? 'bg-green-100 text-green-700' : '' ?>
                                        <?= $confirm['status'] == 'ditolak' ? 'bg-red-100 text-red-700' : '' ?>">
                                        <?= ucfirst($confirm['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?= date('d M Y H:i', strtotime($confirm['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-center">

                                    <?php if ($confirm['status'] == 'menunggu'): ?>

                                        <form action="/admin/confirm/approve/<?= $confirm['id'] ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="/admin/confirm/reject/<?= $confirm['id'] ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
                                                Tolak
                                            </button>
                                        </form>

                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">Sudah diproses</span>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-6 text-slate-400"> Tidak ada data
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>