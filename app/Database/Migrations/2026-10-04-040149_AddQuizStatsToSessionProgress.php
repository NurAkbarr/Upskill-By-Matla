<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuizStatsToSessionProgress extends Migration
{
    public function up()
    {
        $fields = [];

        if (! $this->db->fieldExists('correct_count', 'session_progress')) {
            $fields['correct_count'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
                'after'      => 'quiz_score',
            ];
        }

        if (! $this->db->fieldExists('total_questions', 'session_progress')) {
            $fields['total_questions'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
                'after'      => 'quiz_score',
            ];
        }

        if (! $this->db->fieldExists('duration_seconds', 'session_progress')) {
            $fields['duration_seconds'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
                'after'      => 'quiz_score',
            ];
        }

        if (! empty($fields)) {
            $this->forge->addColumn('session_progress', $fields);
        }
    }

    public function down()
    {
        $fields = ['correct_count', 'total_questions', 'duration_seconds'];
        foreach ($fields as $field) {
            if ($this->db->fieldExists($field, 'session_progress')) {
                $this->forge->dropColumn('session_progress', $field);
            }
        }
    }
}
