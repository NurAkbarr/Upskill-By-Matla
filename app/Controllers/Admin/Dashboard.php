<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\SessionProgressModel;
use App\Models\UserModel;
use Config\Database;

class Dashboard extends BaseController
{
    /**
     * Halaman Utama Super Admin Panel
     * Mengambil dan menampilkan data aktual real-time dari database.
     * Terlindungi oleh filter AuthGuard & RoleGuard:super_admin
     *
     * @return string
     */
    public function index(): string
    {
        $userModel            = new UserModel();
        $courseModel          = new CourseModel();
        $lessonModel          = new LessonModel();
        $sessionProgressModel = new SessionProgressModel();
        $db                   = Database::connect();

        // 1. Hitung total peserta terdaftar (role peserta/non-admin/non-mentor)
        $totalUsers = $userModel
            ->whereNotIn('role', ['super_admin', 'admin', 'mentor'])
            ->countAllResults();

        // 2. Hitung total pengelola (admin, super_admin, dan mentor)
        $totalAdmins = $userModel
            ->whereIn('role', ['super_admin', 'admin', 'mentor'])
            ->countAllResults();

        // 3. Hitung katalog kelas & kelas berstatus published
        $totalCourses     = $courseModel->countAllResults();
        $publishedCourses = $courseModel->where('status', 'published')->countAllResults();

        // 4. Hitung total seluruh sesi materi di tabel lessons
        $totalSessions = $lessonModel->countAllResults();

        // 5. Hitung total ujian kuis CBT yang telah diselesaikan peserta
        $totalQuizAttempts = $sessionProgressModel
            ->groupStart()
                ->where('quiz_completed', 1)
                ->orWhere('quiz_score IS NOT NULL')
            ->groupEnd()
            ->countAllResults();

        // 6. Hitung ujian kuis yang masih berstatus 'pending_review' (koreksi essay manual)
        $pendingEssayCount = $sessionProgressModel
            ->where('grading_status', 'pending_review')
            ->groupStart()
                ->where('quiz_completed', 1)
                ->orWhere('quiz_score IS NOT NULL')
            ->groupEnd()
            ->countAllResults();

        // 7. Ambil 5 data pengerjaan kuis CBT terbaru (join tabel terkait)
        $recentSubmissions = $db->table('session_progress sp')
            ->select('
                sp.id,
                sp.user_id,
                sp.session_id,
                sp.quiz_score,
                sp.duration_seconds,
                sp.correct_count,
                sp.total_questions,
                sp.grading_status,
                sp.quiz_completed_at,
                sp.completed_at,
                u.full_name as student_name,
                u.email as student_email,
                l.chapter_title as session_title,
                c.title as course_title
            ')
            ->join('users u', 'u.id = sp.user_id')
            ->join('lessons l', 'l.id = sp.session_id')
            ->join('courses c', 'c.id = l.course_id')
            ->groupStart()
                ->where('sp.quiz_completed', 1)
                ->orWhere('sp.quiz_score IS NOT NULL')
            ->groupEnd()
            ->orderBy('COALESCE(sp.quiz_completed_at, sp.completed_at)', 'DESC', false)
            ->orderBy('sp.id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'title'               => 'Panel Super Admin - UPSKILL by MATLA',
            'admin_name'          => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email'         => session()->get('email') ?? 'admin@matla.id',
            'total_users'         => $totalUsers,
            'total_admins'        => $totalAdmins,
            'total_courses'       => $totalCourses,
            'published_courses'   => $publishedCourses,
            'total_sessions'      => $totalSessions,
            'total_quiz_attempts' => $totalQuizAttempts,
            'pending_essay_count' => $pendingEssayCount,
            'recent_submissions'  => $recentSubmissions,
        ];

        if (is_file(APPPATH . 'Views/admin/dashboard/index.php')) {
            return view('admin/dashboard/index', $data);
        }

        return view('admin/dashboard', $data);
    }
}
