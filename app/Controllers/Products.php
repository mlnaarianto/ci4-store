<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\StoreModel;

class Products extends BaseController
{
    protected $productModel;
    protected $storeModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->storeModel   = new StoreModel();
    }

    // ================= CREATE =================
    public function create()
    {
        return view('product/create');
    }

    // ================= STORE =================
    public function store()
    {
        $store = $this->storeModel
            ->where('user_id', session('user_id'))
            ->first();

        // 🔒 jika belum punya toko
        if (!$store) {
            return redirect()->to('/store')->with('error', 'Buat toko dulu');
        }

        $nama = $this->request->getPost('nama_produk');

        // ================= GENERATE SLUG =================
        $baseSlug = url_title($nama, '-', true);
        $slug = $baseSlug;

        $i = 1;
        while ($this->productModel->where('slug', $slug)->countAllResults() > 0) {
            $slug = $baseSlug . '-' . $i++;
        }

        // ================= SAVE =================
        $this->productModel->save([
            'store_id'     => $store['id'],
            'category_id'  => $this->request->getPost('category_id') ?: null,
            'nama_produk'  => $nama,
            'slug'         => $slug,
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'harga_min'    => $this->request->getPost('harga_min'),
            'harga_max'    => $this->request->getPost('harga_max'),
            'stok_total'   => $this->request->getPost('stok_total'),
            'berat'        => $this->request->getPost('berat'),
            'kondisi'      => $this->request->getPost('kondisi'),
            'status'       => 'aktif',
        ]);

        return redirect()->to('/store')->with('success', 'Produk berhasil ditambahkan');
    }
    // ================= EDIT =================
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        $store = $this->storeModel
            ->where('user_id', session('user_id'))
            ->first();

        // 🔒 Proteksi ownership
        if (!$product || !$store || $product['store_id'] != $store['id']) {
            return redirect()->to('/store')->with('error', 'Akses ditolak');
        }

        return view('product/edit', [
            'product' => $product
        ]);
    }

    // ================= UPDATE =================
    public function update($id)
    {
        $product = $this->productModel->find($id);

        $store = $this->storeModel
            ->where('user_id', session('user_id'))
            ->first();

        if (!$product || !$store || $product['store_id'] != $store['id']) {
            return redirect()->to('/store')->with('error', 'Akses ditolak');
        }

        $nama = $this->request->getPost('nama_produk');

        // ================= GENERATE SLUG =================
        $baseSlug = url_title($nama, '-', true);
        $slug = $baseSlug;

        $i = 1;
        while (
            $this->productModel
            ->where('slug', $slug)
            ->where('id !=', $id) // 🔥 penting (exclude diri sendiri)
            ->countAllResults() > 0
        ) {
            $slug = $baseSlug . '-' . $i++;
        }

        // ================= UPDATE =================
        $this->productModel->update($id, [
            'category_id' => $this->request->getPost('category_id') ?: null,
            'nama_produk' => $nama,
            'slug'        => $slug,
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'harga_min'   => $this->request->getPost('harga_min'),
            'harga_max'   => $this->request->getPost('harga_max'),
            'stok_total'  => $this->request->getPost('stok_total'),
            'berat'       => $this->request->getPost('berat'),
            'kondisi'     => $this->request->getPost('kondisi'),
        ]);

        return redirect()->to('/store')->with('success', 'Produk berhasil diupdate');
    }

    // ================= DELETE =================
    public function delete($id)
    {
        $product = $this->productModel->find($id);

        $store = $this->storeModel
            ->where('user_id', session('user_id'))
            ->first();

        if (!$product || !$store || $product['store_id'] != $store['id']) {
            return redirect()->to('/store')->with('error', 'Akses ditolak');
        }

        $this->productModel->delete($id);

        return redirect()->to('/store')->with('success', 'Produk berhasil dihapus');
    }
}
