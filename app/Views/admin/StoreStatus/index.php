<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto py-6 px-4">

    <h1 class="text-2xl font-bold mb-6">Manajemen Status Toko</h1>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Toko</th>
                    <th class="px-4 py-3 text-left">Deskripsi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                <?php foreach ($stores as $store): ?>
                    <tr class="hover:bg-gray-50">

                        <!-- Nama toko -->
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">

                                <?php if (!empty($store['logo'])): ?>
                                    <a href="<?= base_url('store/image/logo/' . $store['logo']) ?>" target="_blank">
                                        <img
                                            src="<?= base_url('store/image/logo/' . $store['logo'] . '?v=' . time()) ?>"
                                            onerror="this.src='<?= base_url('images/default.png') ?>'"
                                            class="w-10 h-10 rounded-full object-cover cursor-pointer hover:scale-110 transition"
                                            alt="Logo Toko">
                                    </a>
                                <?php else: ?>
                                    <img
                                        src="<?= base_url('images/default.png') ?>"
                                        class="w-10 h-10 rounded-full object-cover"
                                        alt="Default Logo">
                                <?php endif; ?>

                                <div>
                                    <div class="font-semibold">
                                        <?= esc($store['nama_toko']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?= esc($store['alamat']) ?>
                                    </div>
                                </div>

                            </div>
                        </td>

                        <!-- Deskripsi -->
                        <td class="px-4 py-3 text-gray-600">
                            <?= substr(strip_tags($store['deskripsi']), 0, 80) ?>
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-3">
                            <?php if ($store['status'] == 'pending'): ?>
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                                    Pending
                                </span>
                            <?php elseif ($store['status'] == 'aktif'): ?>
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                    Ditolak
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 py-3 space-x-2">

                            <?php if ($store['status'] == 'pending'): ?>

                                <!-- Approve -->
                                <a href="<?= base_url('/admin/stores/approve/' . $store['id']) ?>"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                    Approve
                                </a>

                                <!-- Reject -->
                                <form action="<?= base_url('/admin/stores/reject/' . $store['id']) ?>"
                                    method="post"
                                    class="inline">

                                    <?= csrf_field() ?>

                                    <input type="hidden" name="rejection_reason" value="Tidak memenuhi syarat">

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                        Reject
                                    </button>
                                </form>

                            <?php endif; ?>

                            <!-- Edit -->
                            <a href="<?= base_url('/admin/stores/edit/' . $store['id']) ?>"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs">
                                Edit
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($stores)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Tidak ada data toko
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>