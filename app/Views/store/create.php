<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto py-8">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Buat Toko Baru</h1>
        <p class="text-slate-500 text-sm mt-1">
            Lengkapi informasi toko dan upload logo agar terlihat profesional.
        </p>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- FORM CARD -->
    <div class="bg-white shadow rounded-2xl p-8">

        <form action="<?= base_url('store/store') ?>" 
              method="post" 
              enctype="multipart/form-data">

            <?= csrf_field() ?>

            <!-- NAMA TOKO -->
            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">
                    Nama Toko <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_toko"
                       value="<?= old('nama_toko') ?>"
                       placeholder="Contoh: Toko Elektronik Jaya"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none text-sm">
            </div>

            <!-- DESKRIPSI -->
            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">
                    Deskripsi Toko
                </label>
                <textarea name="deskripsi"
                          rows="4"
                          placeholder="Ceritakan tentang toko kamu..."
                          class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none text-sm"><?= old('deskripsi') ?></textarea>
            </div>

            <!-- ALAMAT -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">
                    Alamat Toko
                </label>
                <input type="text"
                       name="alamat"
                       value="<?= old('alamat') ?>"
                       placeholder="Contoh: Jakarta Selatan"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none text-sm">
            </div>

            <!-- LOGO -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">
                    Logo Toko <span class="text-red-500">*</span>
                </label>

                <input type="file"
                       name="logo"
                       accept="image/*"
                       onchange="previewLogo(event)"
                       class="w-full text-sm">

                <p class="text-xs text-slate-400 mt-1">
                    Format JPG/PNG, maksimal 2MB
                </p>

                <img id="logoPreview"
                     class="mt-4 w-32 h-32 object-cover rounded-xl hidden border">
            </div>

            <!-- BANNER -->
            <div class="mb-8">
                <label class="block text-sm font-medium mb-2">
                    Banner Toko
                </label>

                <input type="file"
                       name="banner"
                       accept="image/*"
                       onchange="previewBanner(event)"
                       class="w-full text-sm">

                <p class="text-xs text-slate-400 mt-1">
                    Format JPG/PNG, maksimal 4MB
                </p>

                <img id="bannerPreview"
                     class="mt-4 w-full h-40 object-cover rounded-xl hidden border">
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center">

                <a href="<?= base_url('store') ?>"
                   class="text-sm text-slate-500 hover:underline">
                    ← Kembali
                </a>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm transition">
                    Simpan & Ajukan Toko
                </button>

            </div>

        </form>
    </div>

    <!-- INFO NOTE -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm rounded-xl p-4">
        Setelah membuat toko, status akan <strong>pending</strong> dan menunggu persetujuan admin.
    </div>

</div>

<!-- IMAGE PREVIEW SCRIPT -->
<script>
function previewLogo(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('logoPreview');
        output.src = reader.result;
        output.classList.remove('hidden');
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewBanner(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('bannerPreview');
        output.src = reader.result;
        output.classList.remove('hidden');
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?= $this->endSection() ?>