<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Store extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // FK ke users (penjual)
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'nama_toko' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 170,
                'unique'     => true,
            ],

            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'banner' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'aktif', 'ditolak'],
                'default'    => 'pending',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        // supaya 1 user cuma boleh 1 toko
        $this->forge->addUniqueKey('user_id');

        // foreign key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('stores');
    }

    public function down()
    {
        $this->forge->dropTable('stores');
    }
}