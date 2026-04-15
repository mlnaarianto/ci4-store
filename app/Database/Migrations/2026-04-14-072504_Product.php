<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Product extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'store_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'category_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],

            'nama_produk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],

            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'harga_min' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],

            'harga_max' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],

            'stok_total' => [
                'type' => 'INT',
                'default' => 0,
            ],

            'berat' => [
                'type' => 'INT',
                'null' => true,
            ],

            'kondisi' => [
                'type' => 'ENUM',
                'constraint' => ['baru', 'bekas'],
                'default' => 'baru',
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'aktif', 'nonaktif', 'diblokir'],
                'default' => 'draft',
            ],

            'dilihat' => [
                'type' => 'INT',
                'default' => 0,
            ],

            'terjual' => [
                'type' => 'INT',
                'default' => 0,
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

        // PRIMARY KEY
        $this->forge->addKey('id', true);

        // INDEX (biar query lebih cepat)
        $this->forge->addKey('store_id');
        $this->forge->addKey('category_id');

        // FOREIGN KEY
        $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
