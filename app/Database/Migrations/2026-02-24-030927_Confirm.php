<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Confirm extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'no_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],

            'foto_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'foto_selfie_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['menunggu', 'disetujui', 'ditolak'],
                'default' => 'menunggu',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('confirms');
    }

    public function down()
    {
        $this->forge->dropTable('confirms');
    }
}