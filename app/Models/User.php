<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'avatar',
        'avatar_url',
        'google_id',
        'token',
        'token_expired_at'
    ];

    protected $useTimestamps = true;

    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getByGoogleId($googleId)
    {
        return $this->where('google_id', $googleId)->first();
    }
}
