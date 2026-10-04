<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnswersJsonAndGradingStatusToSessionProgress extends Migration
{
    public function up()
    {
        $fields = [];

        if (! $this->db->fieldExists('answers_json', 'session_progress')) {
            $fields['answers_json'] = [
                'type'  => 'LONGTEXT',
                'null'  => true,
                'after' => 'duration_seconds',
            ];
        }

        if (! $this->db->fieldExists('grading_status', 'session_progress')) {
            $fields['grading_status'] = [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'graded',
                'after'      => 'duration_seconds',
            ];
        }

        if (! empty($fields)) {
            $this->forge->addColumn('session_progress', $fields);
        }
    }

    public function down()
    {
        $fields = ['answers_json', 'grading_status'];
        foreach ($fields as $field) {
            if ($this->db->fieldExists($field, 'session_progress')) {
                $this->forge->dropColumn('session_progress', $field);
            }
        }
    }
}
