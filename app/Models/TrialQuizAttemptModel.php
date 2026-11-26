<?php

namespace App\Models;

use CodeIgniter\Model;

class TrialQuizAttemptModel extends Model
{
    protected $table            = 'trial_quiz_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quiz_id',
        'user_id',
        'attempt_token',
        'score',
        'correct_count',
        'total_questions',
        'duration_seconds',
        'answers_json',
        'claimed_at',
        'completed_at',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function findByToken(string $token): ?array
    {
        return $this->where('attempt_token', $token)->first();
    }

    /**
     * Mengklaim attempt tamu (user_id IS NULL) agar tersimpan ke akun peserta setelah daftar/login.
     */
    public function claimByToken(string $token, int $userId): bool
    {
        $attempt = $this->findByToken($token);
        if (! $attempt) {
            return false;
        }

        // Hanya klaim jika belum dimiliki oleh akun lain
        if ($attempt['user_id'] !== null && (int) $attempt['user_id'] !== $userId) {
            return false;
        }

        if ($attempt['user_id'] === null) {
            return (bool) $this->update($attempt['id'], [
                'user_id'    => $userId,
                'claimed_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }
}

