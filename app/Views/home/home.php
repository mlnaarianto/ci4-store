<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">

    <h2 class="text-2xl font-bold mb-6">
        Selamat datang di CI4-Toko

    </h2>

    <?php if ($keyword): ?>
        <p class="mb-4 text-slate-500">
            Hasil pencarian: <b><?= esc($keyword) ?></b>
        </p>
    <?php endif ?>

    <?= $this->include('components/dashboard-cards') ?>

</div>

<?= $this->endSection() ?>