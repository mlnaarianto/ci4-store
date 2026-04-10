<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h2>Halaman Chat (Dummy)</h2>

<div style="border:1px solid #ccc; padding:10px; width:300px">
    <p><strong>User A:</strong> Halo!</p>
    <p><strong>User B:</strong> Hai juga 😊</p>
    <p><strong>User A:</strong> Lagi ngoding ya?😍</p>
</div>

<br>

<input type="text" placeholder="Ketik pesan...">
<button>Kirim</button>

<?= $this->endSection() ?>