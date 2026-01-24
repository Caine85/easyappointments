<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_locations_providers_table extends EA_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('locations_providers')) {
            $this->dbforge->add_field([
                'location_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE
                ],
                'user_id' => [ // Provider ID
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE
                ],
            ]);
            $this->dbforge->add_key(['location_id', 'user_id'], TRUE);
            $this->dbforge->create_table('locations_providers');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('locations_providers')) {
            $this->dbforge->drop_table('locations_providers');
        }
    }
}
