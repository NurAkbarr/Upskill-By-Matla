<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LessonModel;
use App\Models\QuizQuestionModel;
use App\Models\SessionProgressModel;
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

        // Logika Urutan Krusial (Tahap 30):
        // 1. Status 'graded' berada di atas 'pending_review'
        // 2. Nilai tertinggi di atas (DESC)
        // 3. Durasi pengerjaan tercepat di atas (ASC)
        // 4. Waktu submit paling awal di atas (ASC)
        $builder->orderBy("CASE WHEN session_progress.grading_status = 'graded' THEN 0 ELSE 1 END", "ASC", false);
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

        // Ambil butir soal untuk modal koreksi manual
        $quizQuestionModel = new QuizQuestionModel();
        $reportSessionIds  = array_unique(array_filter(array_column($reports, 'session_id')));
        $sessionQuestions  = [];
        if (!empty($reportSessionIds)) {
            $allQuestions = $quizQuestionModel->whereIn('session_id', $reportSessionIds)->orderBy('id', 'ASC')->findAll();
            foreach ($allQuestions as $q) {
                $sessionQuestions[(int)$q['session_id']][] = $q;
            }
        }

        $data = [
            'title'            => 'Laporan Nilai & Peringkat Peserta - MUSLIM UPSKILL ACADEMY',
            'reports'          => $reports,
            'search'           => $search,
            'sessionId'        => $sessionId,
            'sessions'         => $sessions,
            'sessionQuestions' => $sessionQuestions,
            'admin_name'       => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email'      => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/reports/index', $data);
    }

    /**
     * Menyimpan hasil koreksi manual nilai kuis oleh Admin (Tahap 30)
     *
     * @param int $id ID baris pada tabel session_progress
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function grade_submission(int $id)
    {
        $progressModel = new SessionProgressModel();
        $attempt = $progressModel->find($id);

        if (!$attempt) {
            return redirect()->to(base_url('admin/reports'))->with('error', 'Data riwayat ujian tidak ditemukan.');
        }

        $correctCount = (int) $this->request->getPost('correct_count');
        $quizScore    = (float) $this->request->getPost('quiz_score');

        // Batasi rentang nilai 0 - 100
        $quizScore    = max(0, min(100, $quizScore));
        $totalQ       = (int) ($attempt['total_questions'] ?? 100);
        $correctCount = max(0, min($totalQ > 0 ? $totalQ : 100, $correctCount));

        $progressModel->update($id, [
            'correct_count'  => $correctCount,
            'quiz_score'     => $quizScore,
            'grading_status' => 'graded',
        ]);

        return redirect()->back()->with('success', 'Nilai kuis peserta berhasil diperbarui dan peringkat telah ditetapkan.');
    }
}
