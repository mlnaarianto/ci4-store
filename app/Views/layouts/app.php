<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'CI4 Toko' ?></title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-100 text-slate-700 min-h-screen flex flex-col">

    <?= $this->include('components/navbar') ?>

    <main class="pt-20 flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('components/footer') ?>

</body>

</html>