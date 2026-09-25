<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizQuestionModel extends Model
{
    protected $table            = 'quiz_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'session_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false; // Kolom created_at ditangani oleh MySQL CURRENT_TIMESTAMP

    /**
     * Mengambil seluruh butir soal untuk sesi tertentu
     *
     * @param int $sessionId
     * @return array
     */
    public function getQuestionsBySession(int $sessionId): array
    {
        return $this->where('session_id', $sessionId)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Menghitung total soal yang tersedia pada sesi tertentu
     *
     * @param int $sessionId
     * @return int
     */
    public function countBySession(int $sessionId): int
    {
        return $this->where('session_id', $sessionId)->countAllResults();
    }
}
