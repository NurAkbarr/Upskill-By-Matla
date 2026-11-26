<?php

namespace App\Models;

use CodeIgniter\Model;

class TrialQuizModel extends Model
{
    protected $table            = 'trial_quizzes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'slug',
        'topic_label',
        'description',
        'is_trial',
        'status',
        'duration_minutes',
        'recommended_course_id',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    /**
     * Membuat slug unik secara otomatis dari judul quiz.
     */
    protected function generateSlug(array $data): array
    {
        if (isset($data['data']['title']) && ! empty($data['data']['title'])) {
            helper('url');
            $baseSlug = url_title($data['data']['title'], '-', true);
            $slug     = $baseSlug !== '' ? $baseSlug : 'quiz';

            $builder   = $this->builder();
            $currentId = $data['id'][0] ?? null;

            $builder->where('slug', $slug);
            if ($currentId) {
                $builder->where('id !=', $currentId);
            }

            if ($builder->countAllResults() > 0) {
                $slug .= '-' . substr(md5(uniqid((string) mt_rand(), true)), 0, 5);
            }

            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    /**
     * Mengambil 1 Quiz Trial Utama yang berstatus published dan memiliki minimal 1 soal.
     */
    public function getFeaturedTrial(): ?array
    {
        $row = $this->db->table('trial_quizzes tq')
            ->select('tq.*, COUNT(q.id) as question_count')
            ->join('trial_quiz_questions q', 'q.quiz_id = tq.id', 'inner')
            ->where('tq.status', 'published')
            ->where('tq.is_trial', 1)
            ->groupBy('tq.id')
            ->orderBy('tq.updated_at', 'DESC')
            ->orderBy('tq.id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Mengambil daftar quiz published lainnya (selain quiz yang sedang dilihat) yang sudah memiliki soal.
     */
    public function getOtherPublishedQuizzes(int $excludeId): array
    {
        return $this->db->table('trial_quizzes tq')
            ->select('tq.*, COUNT(q.id) as question_count')
            ->join('trial_quiz_questions q', 'q.quiz_id = tq.id', 'inner')
            ->where('tq.status', 'published')
            ->where('tq.id !=', $excludeId)
            ->groupBy('tq.id')
            ->orderBy('tq.is_trial', 'DESC')
            ->orderBy('tq.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Mengambil seluruh quiz beserta jumlah soal, jumlah pengerjaan, dan judul kelas rekomendasi (untuk Admin).
     */
    public function getAllWithStats(): array
    {
        return $this->db->table('trial_quizzes tq')
            ->select('
                tq.*,
                c.title as recommended_course_title,
                (SELECT COUNT(*) FROM trial_quiz_questions qq WHERE qq.quiz_id = tq.id) as question_count,
                (SELECT COUNT(*) FROM trial_quiz_attempts qa WHERE qa.quiz_id = tq.id) as attempt_count
            ')
            ->join('courses c', 'c.id = tq.recommended_course_id', 'left')
            ->orderBy('tq.is_trial', 'DESC')
            ->orderBy('tq.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Memastikan hanya 1 quiz yang aktif sebagai Trial Utama (is_trial = 1).
     */
    public function clearOtherTrials(?int $exceptId = null): void
    {
        $builder = $this->db->table($this->table)->set('is_trial', 0);
        if ($exceptId !== null) {
            $builder->where('id !=', $exceptId);
        }
        $builder->update();
    }
}

