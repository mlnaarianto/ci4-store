<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto py-6 px-4">

    <h2 class="text-xl font-bold mb-4">Edit Status Toko</h2>

    <div class="bg-white shadow rounded-xl p-6">

        <form action="<?= base_url('/admin/stores/update/' . $store['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Toko</label>
                <input type="text"
                    value="<?= esc($store['nama_toko']) ?>"
                    disabled
                    class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status"
                    id="status"
                    onchange="toggleAlasan()"
                    class="w-full border rounded px-3 py-2">

                    <option value="pending" <?= $store['status'] == 'pending' ? 'selected' : '' ?>>
                        Pending
                    </option>

                    <option value="aktif" <?= $store['status'] == 'aktif' ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="ditolak" <?= $store['status'] == 'ditolak' ? 'selected' : '' ?>>
                        Ditolak
                    </option>

                </select>
            </div>

            <!-- ALASAN -->
            <div class="mb-4" id="alasanField">
                <label class="block text-sm font-medium mb-1">Alasan Penolakan</label>
                <textarea name="alasan"
                    id="alasan"
                    class="w-full border rounded px-3 py-2"
                    placeholder="Masukkan alasan penolakan..."><?= esc($store['alasan'] ?? '') ?></textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="/admin/stores"
                    class="bg-gray-400 text-white px-4 py-2 rounded">
                    Kembali
                </a>

                <button type="submit"
                    onclick="return validateForm()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>

<script>
    function toggleAlasan() {
        const status = document.getElementById('status').value;
        const alasanField = document.getElementById('alasanField');

        if (status === 'ditolak') {
            alasanField.style.display = 'block';
        } else {
            alasanField.style.display = 'none';
        }
    }

    // validasi sebelum submit
    function validateForm() {
        const status = document.getElementById('status').value;
        const alasan = document.getElementById('alasan').value;

        if (status === 'ditolak' && alasan.trim() === '') {
            alert('Alasan penolakan wajib diisi!');
            return false;
        }

        return true;
    }

    // jalankan saat load
    document.addEventListener("DOMContentLoaded", function() {
        toggleAlasan();
    });
</script>

<?= $this->endSection() ?>