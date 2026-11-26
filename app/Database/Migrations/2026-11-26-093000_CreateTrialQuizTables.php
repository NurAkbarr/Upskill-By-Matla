<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrialQuizTables extends Migration
{
    public function up()
    {
        // 1. Tabel trial_quizzes (Quiz Publik / Trial & Quiz Mandiri Khusus Member)
        if (! $this->db->tableExists('trial_quizzes')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'title' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                ],
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 220,
                ],
                'topic_label' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 80,
                    'null'       => true,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'is_trial' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['draft', 'published'],
                    'default'    => 'draft',
                ],
                'duration_minutes' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'recommended_course_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'created_by' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('slug', 'uk_trial_quizzes_slug');
            $this->forge->addKey(['status', 'is_trial'], false, false, 'idx_trial_quizzes_status_trial');
            $this->forge->addForeignKey('recommended_course_id', 'courses', 'id', 'CASCADE', 'SET NULL', 'fk_trial_quizzes_course');
            $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL', 'fk_trial_quizzes_creator');
            $this->forge->createTable('trial_quizzes', true, ['ENGINE' => 'InnoDB']);
        }

        // 2. Tabel trial_quiz_questions (Butir Soal PG + Pembahasan & Dalil)
        if (! $this->db->tableExists('trial_quiz_questions')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'quiz_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'question_text' => [
                    'type' => 'TEXT',
                ],
                'option_a' => [
                    'type' => 'TEXT',
                ],
                'option_b' => [
                    'type' => 'TEXT',
                ],
                'option_c' => [
                    'type' => 'TEXT',
                ],
                'option_d' => [
                    'type' => 'TEXT',
                ],
                'correct_answer' => [
                    'type'       => 'ENUM',
                    'constraint' => ['a', 'b', 'c', 'd'],
                ],
                'explanation' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'order_index' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'default'    => 1,
                ],
                'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey(['quiz_id', 'order_index'], false, false, 'idx_trial_questions_quiz_order');
            $this->forge->addForeignKey('quiz_id', 'trial_quizzes', 'id', 'CASCADE', 'CASCADE', 'fk_trial_questions_quiz');
            $this->forge->createTable('trial_quiz_questions', true, ['ENGINE' => 'InnoDB']);
        }

        // 3. Tabel trial_quiz_attempts (Riwayat Pengerjaan Guest & Member + Auto-Claim)
        if (! $this->db->tableExists('trial_quiz_attempts')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'quiz_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'attempt_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 64,
                ],
                'score' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'default'    => 0.00,
                ],
                'correct_count' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'total_questions' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'duration_seconds' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'answers_json' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                ],
                'claimed_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'completed_at' => [
                    'type' => 'DATETIME',
                ],
                'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('attempt_token', 'uk_trial_attempts_token');
            $this->forge->addKey('quiz_id', false, false, 'idx_trial_attempts_quiz');
            $this->forge->addKey('user_id', false, false, 'idx_trial_attempts_user');
            $this->forge->addForeignKey('quiz_id', 'trial_quizzes', 'id', 'CASCADE', 'CASCADE', 'fk_trial_attempts_quiz');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL', 'fk_trial_attempts_user');
            $this->forge->createTable('trial_quiz_attempts', true, ['ENGINE' => 'InnoDB']);
        }
    }

    public function down()
    {
        $this->forge->dropTable('trial_quiz_attempts', true);
        $this->forge->dropTable('trial_quiz_questions', true);
        $this->forge->dropTable('trial_quizzes', true);
    }
}

