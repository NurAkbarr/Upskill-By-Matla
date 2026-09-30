<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'course_id',
        'progress_percentage',
        'status',
        'enrolled_at',
    ];

    // Dates
    protected $useTimestamps = false; // enrolled_at ditangani oleh DEFAULT CURRENT_TIMESTAMP di MySQL

    /**
     * Mengambil daftar kursus yang diikuti oleh peserta tertentu beserta informasi kelas dan mentor
     *
     * @param int $userId
     * @return array
     */
    public function getEnrolledCoursesByUser(int $userId): array
    {
        return $this->select('courses.*, enrollments.id as enrollment_id, enrollments.progress_percentage, enrollments.status as enrollment_status, enrollments.enrolled_at, users.full_name as mentor_name')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('users', 'users.id = courses.mentor_id', 'left')
                    ->where('enrollments.user_id', $userId)
                    ->orderBy('enrollments.id', 'DESC')
                    ->findAll();
    }

    /**
     * Memeriksa apakah user sudah terdaftar di kelas tertentu
     *
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function isEnrolled(int $userId, int $courseId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->countAllResults() > 0;
    }

    /**
     * Mendaftarkan peserta ke kelas tertentu (Sistem Langsung / MVP Gratis)
     *
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function enrollUser(int $userId, int $courseId): bool
    {
        if ($this->isEnrolled($userId, $courseId)) {
            return true;
        }

        return (bool) $this->insert([
            'user_id'             => $userId,
            'course_id'           => $courseId,
            'progress_percentage' => 0,
            'status'              => 'active',
        ]);
    }
}
