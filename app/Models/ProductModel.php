<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'store_id',
        'category_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga_min',
        'harga_max',
        'stok_total',
        'berat',
        'kondisi',
        'status',
        'dilihat',
        'terjual',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // =========================
    // 🔥 JOIN ke category & store
    // =========================
    public function getProductsWithRelation()
    {
        return $this->select('products.*, categories.nama as category_nama, stores.nama_toko')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('stores', 'stores.id = products.store_id', 'left')
            ->findAll();
    }

    // =========================
    // 🔥 Filter by category
    // =========================
    public function getByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)->findAll();
    }
}