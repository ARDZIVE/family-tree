<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFamilyMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'birth_year' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
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
        $this->forge->addForeignKey('parent_id', 'family_members', 'id', 'SET NULL');
        $this->forge->createTable('family_members');
    }

    public function down()
    {
        $this->forge->dropTable('family_members');
    }
}