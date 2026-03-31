<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CI4 Toko' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-100 text-slate-700 min-h-screen flex flex-col">

    <?= $this->include('components/navbar') ?>

    <main class="pt-20 flex-1 container mx-auto px-4">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('components/footer') ?>

    <!-- GLOBAL SWEETALERT HANDLER -->
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: <?= json_encode(session()->getFlashdata('success')) ?>,
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: <?= json_encode(session()->getFlashdata('error')) ?>,
                confirmButtonText: 'Tutup'
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: <?= json_encode(session()->getFlashdata('warning')) ?>,
                confirmButtonText: 'Mengerti'
            });
        </script>
    <?php endif; ?>

</body>

</html>