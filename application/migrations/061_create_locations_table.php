<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_locations_table extends EA_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('locations')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE
                ],
                'city' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE
                ],
                'zip_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ],
                'phone_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ]
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('locations');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('locations')) {
            $this->dbforge->drop_table('locations');
        }
    }
}
