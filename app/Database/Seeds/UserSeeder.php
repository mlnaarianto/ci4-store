<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'  => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'nomor_hp' => '081234567890',
                'alamat' => 'Batam, Kepulauan Riau',
                'role' => 'admin',
                'status' => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'  => 'Penjual Demo',
                'email' => 'penjual@gmail.com',
                'password' => password_hash('penjual123', PASSWORD_DEFAULT),
                'nomor_hp' => '082345678901',
                'alamat' => 'Bengkong, Batam',
                'role' => 'penjual',
                'status' => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('users')->insertBatch($users);
    }
}