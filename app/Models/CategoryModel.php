<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nama',
        'slug',
        'parent_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // =========================
    // 🔥 Ambil kategori utama
    // =========================
    public function getParentCategories()
    {
        return $this->where('parent_id', null)->findAll();
    }

    // =========================
    // 🔥 Ambil sub kategori
    // =========================
    public function getChildren($parentId)
    {
        return $this->where('parent_id', $parentId)->findAll();
    }
}