<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div x-data="settingAccountHandler()" class="max-w-xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Profil Akun</h1>

    <!-- INFO -->
    <div
        x-show="showInfo"
        x-transition
        class="mb-4 p-4 bg-blue-50 border border-blue-300 text-blue-800 rounded-lg flex justify-between items-start"
        style="display:none">
        <div class="pr-4">
            <p class="font-semibold mb-1">Perhatian!</p>
            <p class="text-sm">
                Pastikan nomor HP dan alamat yang diinput sudah benar dan valid.
                Data ini digunakan untuk transaksi dan proses verifikasi akun penjual.
            </p>
        </div>
        <button @click="closeInfo"
            class="ml-4 text-blue-700 hover:text-blue-900 font-bold">
            ✕
        </button>
    </div>

    <!-- FLASH SUCCESS -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- VALIDATION -->
    <?php if (session()->getFlashdata('validation')): ?>
        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
            <?= session()->getFlashdata('validation')->listErrors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('account/setting/update') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" required
                value="<?= old('name', $user['name']) ?>"
                class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" disabled
                value="<?= esc($user['email']) ?>"
                class="w-full bg-gray-100 border rounded px-3 py-2 text-gray-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nomor HP</label>
            <input type="text" name="nomor_hp"
                inputmode="numeric"
                pattern="[0-9]*"
                required
                value="<?= old('nomor_hp', $user['nomor_hp']) ?>"
                class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="alamat" required rows="3"
                class="w-full border rounded px-3 py-2"><?= old('alamat', $user['alamat']) ?></textarea>
        </div>

        <button class="w-full bg-blue-600 text-white font-semibold px-4 py-2 rounded">
            Simpan Perubahan
        </button>
    </form>

</div>

<script>
    function settingAccountHandler() {
        return {
            showInfo: true,

            closeInfo() {
                this.showInfo = false;
                localStorage.setItem('setting_account_info_read', '1');
            }
        }
    }

    document.addEventListener('alpine:init', () => {
        const read = localStorage.getItem('setting_account_info_read');
        if (read === '1') {
            const root = document.querySelector('[x-data]');
            if (root && root.__x) {
                root.__x.$data.showInfo = false;
            }
        }
    });
</script>

<?= $this->endSection() ?>