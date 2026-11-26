<?php

namespace App\Models;

use CodeIgniter\Model;

class TrialQuizQuestionModel extends Model
{
    protected $table            = 'trial_quiz_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quiz_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'order_index',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function getByQuiz(int $quizId): array
    {
        return $this->where('quiz_id', $quizId)
            ->orderBy('order_index', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Mengambil soal untuk halaman pengerjaan publik TANPA kunci jawaban dan pembahasan.
     */
    public function getPublicByQuiz(int $quizId): array
    {
        return $this->select('id, quiz_id, question_text, option_a, option_b, option_c, option_d, order_index')
            ->where('quiz_id', $quizId)
            ->orderBy('order_index', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getNextOrderIndex(int $quizId): int
    {
        $last = $this->where('quiz_id', $quizId)
            ->orderBy('order_index', 'DESC')
            ->first();

        return $last ? ((int) $last['order_index'] + 1) : 1;
    }

    public function countByQuiz(int $quizId): int
    {
        return $this->where('quiz_id', $quizId)->countAllResults();
    }
}

