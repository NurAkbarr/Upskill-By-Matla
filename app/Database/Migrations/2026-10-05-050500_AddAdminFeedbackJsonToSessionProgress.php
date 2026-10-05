<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminFeedbackJsonToSessionProgress extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('admin_feedback_json', 'session_progress')) {
            $fields = [
                'admin_feedback_json' => [
                    'type'  => 'LONGTEXT',
                    'null'  => true,
                    'after' => 'answers_json',
                ],
            ];
            $this->forge->addColumn('session_progress', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('admin_feedback_json', 'session_progress')) {
            $this->forge->dropColumn('session_progress', 'admin_feedback_json');
        }
    }
}
