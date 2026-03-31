<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfirmModel extends Model
{
    protected $table            = 'confirms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'no_ktp',
        'foto_ktp',
        'foto_selfie_ktp',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
