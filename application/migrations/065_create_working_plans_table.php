<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_working_plans_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'id_users_provider' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_locations' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'working_plan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'working_plan_exceptions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('id_users_provider');
        $this->dbforge->add_key('id_locations');
        $this->dbforge->create_table('working_plans');

        $users = $this->db->dbprefix('users');
        $locations = $this->db->dbprefix('locations');
        $working_plans = $this->db->dbprefix('working_plans');

        $this->db->query("
            ALTER TABLE `$working_plans`
            ADD CONSTRAINT `working_plans_users_id`
            FOREIGN KEY (`id_users_provider`)
            REFERENCES `$users`(`id`)
            ON DELETE CASCADE ON UPDATE CASCADE
        ");

        $this->db->query("
            ALTER TABLE `$working_plans`
            ADD CONSTRAINT `working_plans_locations_id`
            FOREIGN KEY (`id_locations`)
            REFERENCES `$locations`(`id`)
            ON DELETE CASCADE ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        $this->dbforge->drop_table('working_plans');
    }
}
