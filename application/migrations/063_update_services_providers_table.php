<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Update_services_providers_table extends EA_Migration
{
    public function up()
    {
        if ($this->db->table_exists('services_providers')) {
            $fields = [
                'price_override' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'null' => TRUE,
                    'default' => NULL
                ],
                'duration_override' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    'default' => NULL
                ]
            ];
            
            // Check if columns exist before adding
            if (!$this->db->field_exists('price_override', 'services_providers')) {
                $this->dbforge->add_column('services_providers', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('services_providers')) {
            if ($this->db->field_exists('price_override', 'services_providers')) {
                $this->dbforge->drop_column('services_providers', 'price_override');
            }
            if ($this->db->field_exists('duration_override', 'services_providers')) {
                $this->dbforge->drop_column('services_providers', 'duration_override');
            }
        }
    }
}
