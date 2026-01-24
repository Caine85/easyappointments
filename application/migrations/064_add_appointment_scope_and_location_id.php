<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_appointment_scope_and_location_id extends EA_Migration
{
    public function up()
    {
        // Add location_id to appointments
        if ($this->db->table_exists('appointments') && !$this->db->field_exists('location_id', 'appointments')) {
            $this->dbforge->add_column('appointments', [
                'location_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE,
                    'after' => 'id_services'
                ]
            ]);
        }

        // Add created_by to appointments
        if ($this->db->table_exists('appointments') && !$this->db->field_exists('created_by', 'appointments')) {
            $this->dbforge->add_column('appointments', [
                'created_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE, // Null for public bookings or legacy data
                    'after' => 'location_id'
                ]
            ]);
        }

        // Add appointments_view_scope to users
        if ($this->db->table_exists('users') && !$this->db->field_exists('appointments_view_scope', 'users')) {
            $this->dbforge->add_column('users', [
                'appointments_view_scope' => [
                    'type' => 'ENUM("all","own")',
                    'default' => 'all',
                    'null' => FALSE,
                    'after' => 'notes' 
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->table_exists('appointments')) {
            if ($this->db->field_exists('location_id', 'appointments')) {
                $this->dbforge->drop_column('appointments', 'location_id');
            }
            if ($this->db->field_exists('created_by', 'appointments')) {
                $this->dbforge->drop_column('appointments', 'created_by');
            }
        }

        if ($this->db->table_exists('users')) {
            if ($this->db->field_exists('appointments_view_scope', 'users')) {
                $this->dbforge->drop_column('users', 'appointments_view_scope');
            }
        }
    }
}
