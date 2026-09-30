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
}
