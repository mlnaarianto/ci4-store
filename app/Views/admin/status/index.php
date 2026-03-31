<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Manajemen Status User</h1>


    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <div class="bg-white shadow rounded-xl p-6">

        <table class="w-full text-sm text-left">
            <thead class="border-b">
                <tr>
                    <th class="py-3">Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($users as $u): ?>
                    <tr class="border-b hover:bg-slate-50">
                        <td class="py-3"><?= esc($u['name']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td class="capitalize"><?= esc($u['role']) ?></td>
                        <td>
                            <?php if ($u['status'] === 'aktif'): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                    Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'aktif'): ?>
                                <form action="<?= base_url('admin/status/nonaktifkan/' . $u['id']) ?>" method="post" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="text-red-600 font-semibold">
                                        Nonaktifkan
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="<?= base_url('admin/status/aktifkan/' . $u['id']) ?>" method="post" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="text-green-600 font-semibold">
                                        Aktifkan
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    </div>

</div>

<?= $this->endSection() ?>