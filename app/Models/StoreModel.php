<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreModel extends Model
{
    protected $table            = 'stores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Field yang boleh diinsert / update
    protected $allowedFields = [
        'user_id',
        'nama_toko',
        'slug',
        'logo',
        'banner',
        'deskripsi',
        'alamat',
        'status',
        'alasan',
    ];

    /*
    |--------------------------------------------------------------------------
    | Timestamps
    |--------------------------------------------------------------------------
    */
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
    protected $validationRules = [
        'user_id'   => 'required|integer',
        'nama_toko' => 'required|min_length[3]|max_length[150]',
        'slug' => 'required|min_length[3]|max_length[170]',
        'status'    => 'in_list[pending,aktif,ditolak]',
    ];

    protected $validationMessages = [
        'nama_toko' => [
            'required' => 'Nama toko wajib diisi.',
        ],
        'slug' => [
            'is_unique' => 'Slug sudah digunakan.',
        ],
    ];

    protected $skipValidation = false;
}
