<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow">

    <h2 class="text-xl font-bold mb-6">Edit Produk</h2>

    <form action="<?= base_url('product/update/' . $product['id']) ?>" method="post" class="space-y-5">

        <!-- Nama Produk -->
        <div>
            <label class="text-sm font-medium">Nama Produk</label>
            <input type="text" name="nama_produk"
                value="<?= esc($product['nama_produk']) ?>"
                class="w-full mt-1 border rounded-lg px-3 py-2" required>
        </div>

        <!-- Kategori -->
        <div>
            <label class="text-sm font-medium">Kategori (Opsional)</label>
            <input type="number" name="category_id"
                value="<?= esc($product['category_id'] ?? '') ?>"
                class="w-full mt-1 border rounded-lg px-3 py-2"
                placeholder="Kosongkan jika tidak ada kategori">
        </div>
        <!-- Deskripsi -->
        <div>
            <label class="text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi"
                class="w-full mt-1 border rounded-lg px-3 py-2"><?= esc($product['deskripsi']) ?></textarea>
        </div>

        <!-- Harga -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Harga Min</label>
                <input type="number" name="harga_min"
                    value="<?= esc($product['harga_min']) ?>"
                    class="w-full mt-1 border rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm font-medium">Harga Max</label>
                <input type="number" name="harga_max"
                    value="<?= esc($product['harga_max']) ?>"
                    class="w-full mt-1 border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <!-- Stok -->
        <div>
            <label class="text-sm font-medium">Stok Total</label>
            <input type="number" name="stok_total"
                value="<?= esc($product['stok_total']) ?>"
                class="w-full mt-1 border rounded-lg px-3 py-2" required>
        </div>

        <!-- Berat -->
        <div>
            <label class="text-sm font-medium">Berat</label>
            <input type="number" name="berat"
                value="<?= esc($product['berat']) ?>"
                class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>

        <!-- Kondisi -->
        <div>
            <label class="text-sm font-medium">Kondisi</label>
            <select name="kondisi" class="w-full mt-1 border rounded-lg px-3 py-2">
                <option value="baru" <?= $product['kondisi'] == 'baru' ? 'selected' : '' ?>>Baru</option>
                <option value="bekas" <?= $product['kondisi'] == 'bekas' ? 'selected' : '' ?>>Bekas</option>
            </select>
        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3">
            <a href="<?= base_url('store') ?>" class="px-4 py-2 bg-slate-200 rounded-lg">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>