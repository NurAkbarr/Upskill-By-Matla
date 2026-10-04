<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LessonModel;
use Config\Database;

class Report extends BaseController
{
    /**
     * Menampilkan laporan nilai dan pemeringkatan (leaderboard) kuis peserta
     *
     * @return string
     */
    public function index()
    {
        $db = Database::connect();
        $builder = $db->table('session_progress');
        $builder->select('
            session_progress.*,
            users.full_name as student_name,
            users.email as student_email,
            lessons.chapter_title as session_title,
            courses.id as course_id,
            courses.title as course_title
        ');
        $builder->join('users', 'users.id = session_progress.user_id');
        $builder->join('lessons', 'lessons.id = session_progress.session_id');
        $builder->join('courses', 'courses.id = lessons.course_id');

        // Filter hanya peserta yang sudah menyelesaikan kuis
        $builder->groupStart()
                ->where('session_progress.quiz_completed', 1)
                ->orWhere('session_progress.quiz_score IS NOT NULL')
                ->groupEnd();

        // Filter Pencarian Bebas (Nama Peserta, Judul Kelas, atau Nama Sesi)
        $search    = trim((string) $this->request->getGet('search'));
        $sessionId = (int) $this->request->getGet('session_id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.full_name', $search)
                    ->orLike('courses.title', $search)
                    ->orLike('lessons.chapter_title', $search)
                    ->groupEnd();
        }

        // Filter Spesifik Sesi (Opsional)
        if ($sessionId > 0) {
            $builder->where('session_progress.session_id', $sessionId);
        }

        // Logika Urutan Krusial:
        // 1. Nilai tertinggi di atas (DESC)
        // 2. Durasi pengerjaan tercepat di atas (ASC)
        // 3. Waktu submit paling awal di atas (ASC)
        $builder->orderBy('session_progress.quiz_score', 'DESC');
        $builder->orderBy('session_progress.duration_seconds', 'ASC');
        $builder->orderBy('session_progress.completed_at', 'ASC');

        $reports = $builder->get()->getResultArray();

        // Ambil daftar sesi untuk dropdown filter
        $lessonModel = new LessonModel();
        $sessions = $lessonModel->select('lessons.id, lessons.chapter_title, courses.title as course_title')
                                ->join('courses', 'courses.id = lessons.course_id')
                                ->orderBy('courses.title', 'ASC')
                                ->orderBy('lessons.id', 'ASC')
                                ->findAll();

        $data = [
            'title'       => 'Laporan Nilai & Peringkat Peserta - MUSLIM UPSKILL ACADEMY',
            'reports'     => $reports,
            'search'      => $search,
            'sessionId'   => $sessionId,
            'sessions'    => $sessions,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/reports/index', $data);
    }
}
