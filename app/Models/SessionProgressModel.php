<?php

namespace App\Models;

use CodeIgniter\Model;

class SessionProgressModel extends Model
{
    protected $table            = 'session_progress';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'session_id',
        'is_completed',
        'quiz_completed',
        'quiz_score',
        'correct_count',
        'total_questions',
        'duration_seconds',
        'answers_json',
        'grading_status',
        'quiz_completed_at',
        'completed_at',
    ];

    protected $useTimestamps = false;

    /**
     * Memeriksa apakah user sudah menyelesaikan materi sesi tertentu (100%)
     */
    public function isCompleted(int $userId, int $sessionId): bool
    {
        $row = $this->where('user_id', $userId)
                    ->where('session_id', $sessionId)
                    ->first();

        return !empty($row['is_completed']);
    }

    /**
     * Menandai materi sesi selesai 100% untuk user
     */
    public function markCompleted(int $userId, int $sessionId): bool
    {
        $existing = $this->where('user_id', $userId)
                         ->where('session_id', $sessionId)
                         ->first();

        $data = [
            'user_id'      => $userId,
            'session_id'   => $sessionId,
            'is_completed' => 1,
            'completed_at' => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            return (bool) $this->update($existing['id'], $data);
        }

        return (bool) $this->insert($data);
    }

    /**
     * Mengambil status progres semua sesi yang diberikan untuk seorang user
     * Mengembalikan associative array: [ session_id => bool (is_completed) ]
     */
    public function getProgressMap(int $userId, array $sessionIds): array
    {
        if (empty($sessionIds)) {
            return [];
        }

        $records = $this->where('user_id', $userId)
                        ->whereIn('session_id', $sessionIds)
                        ->findAll();

        $map = [];
        foreach ($records as $r) {
            $map[(int) $r['session_id']] = (bool) $r['is_completed'];
        }

        return $map;
    }

    /**
     * Menandai kuis sesi telah diselesaikan oleh user
     */
    public function markQuizCompleted(
        int $userId, 
        int $sessionId, 
        float $score, 
        int $correctCount = 0, 
        int $totalQuestions = 0, 
        int $durationSeconds = 0,
        ?string $answersJson = null,
        string $gradingStatus = 'graded'
    ): bool
    {
        $existing = $this->where('user_id', $userId)
                         ->where('session_id', $sessionId)
                         ->first();

        $data = [
            'user_id'           => $userId,
            'session_id'        => $sessionId,
            'is_completed'      => 1,
            'quiz_completed'    => 1,
            'quiz_score'        => $score,
            'correct_count'     => $correctCount,
            'total_questions'   => $totalQuestions,
            'duration_seconds'  => $durationSeconds,
            'answers_json'      => $answersJson,
            'grading_status'    => $gradingStatus,
            'quiz_completed_at' => date('Y-m-d H:i:s'),
            'completed_at'      => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            return (bool) $this->update($existing['id'], $data);
        }

        return (bool) $this->insert($data);
    }

    /**
     * Menghitung total kuis yang telah dikerjakan oleh user
     */
    public function getCompletedQuizCount(int $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('quiz_completed', 1)
                    ->countAllResults();
    }

    /**
     * Memeriksa apakah user sudah menyelesaikan kuis untuk sesi tertentu (Tahap 24)
     */
    public function isQuizCompleted(int $userId, int $sessionId): bool
    {
        $row = $this->where('user_id', $userId)
                    ->where('session_id', $sessionId)
                    ->first();

        return !empty($row['quiz_completed']);
    }

    /**
     * Mengambil detail status kuis dan nilai untuk user pada daftar sesi (Tahap 24)
     * Mengembalikan associative array: [ session_id => ['quiz_completed' => bool, 'quiz_score' => float|null] ]
     */
    public function getQuizProgressMap(int $userId, array $sessionIds): array
    {
        if (empty($sessionIds)) {
            return [];
        }

        $records = $this->where('user_id', $userId)
                        ->whereIn('session_id', $sessionIds)
                        ->findAll();

        $map = [];
        foreach ($records as $r) {
            $map[(int) $r['session_id']] = [
                'quiz_completed'    => !empty($r['quiz_completed']),
                'quiz_score'        => $r['quiz_score'] !== null ? (float) $r['quiz_score'] : null,
                'quiz_completed_at' => $r['quiz_completed_at'] ?? null,
            ];
        }

        return $map;
    }

    /**
     * Mereset status kuis untuk semua peserta pada sesi tertentu (Tahap 25)
     * Status baca materi (is_completed) tetap dipertahankan (tidak dihapus/diubah).
     *
     * @param int $sessionId
     * @return bool
     */
    public function resetQuizAttemptsBySession(int $sessionId): bool
    {
        return (bool) $this->where('session_id', $sessionId)
                           ->set([
                               'quiz_completed'    => 0,
                               'quiz_score'        => null,
                               'correct_count'     => 0,
                               'total_questions'   => 0,
                               'duration_seconds'  => 0,
                               'answers_json'      => null,
                               'grading_status'    => 'graded',
                               'quiz_completed_at' => null,
                           ])
                           ->update();
    }
}
