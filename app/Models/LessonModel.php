<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonModel extends Model
{
    protected $table            = 'lessons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id',
        'chapter_title',
        'content_type',
        'content_url_or_text',
        'quiz_url',
        'order_index',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false; // created_at diatur oleh DEFAULT CURRENT_TIMESTAMP di MySQL

    /**
     * Mengambil seluruh sesi pembelajaran berdasarkan ID Kursus (terurut)
     *
     * @param int $courseId
     * @return array
     */
    public function getLessonsByCourse(int $courseId): array
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('order_index', 'ASC')
                    ->findAll();
    }

    /**
     * Mendapatkan order_index otomatis untuk sesi baru berikutnya
     *
     * @param int $courseId
     * @return int
     */
    public function getNextOrderIndex(int $courseId): int
    {
        $last = $this->where('course_id', $courseId)
                     ->orderBy('order_index', 'DESC')
                     ->first();

        return $last ? ((int) $last['order_index'] + 1) : 1;
    }
}
