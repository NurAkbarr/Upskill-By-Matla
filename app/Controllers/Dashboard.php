<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;

class Dashboard extends BaseController
{
    /**
     * Halaman Dasbor Utama Pengguna (Terproteksi Filter AuthGuard)
     */
    public function index()
    {
        // Jaring pengaman: Jika Super Admin mengakses dasbor reguler, alihkan otomatis ke panel admin
        if (session()->get('role') === 'super_admin') {
            return redirect()->to('/admin/dashboard');
        }

        // Ambil informasi pengguna aktif dari session
        $userId       = (int) (session()->get('user_id') ?? 0);
        $userName     = session()->get('full_name') ?? 'Pengguna';
        $userRole     = session()->get('role') ?? 'peserta_b2c';
        $userEmail    = session()->get('email') ?? '';
        $instansiName = session()->get('instansi_name') ?? null;

        // Ambil daftar kelas yang diikuti oleh user aktif melalui EnrollmentModel
        $enrollmentModel  = new EnrollmentModel();
        $enrolled_courses = $userId > 0 ? $enrollmentModel->getEnrolledCoursesByUser($userId) : [];

        // Hitung statistik
        $total_active = count($enrolled_courses);
        $total_progress_sum = 0;
        foreach ($enrolled_courses as $ec) {
            $total_progress_sum += (int) ($ec['progress_percentage'] ?? 0);
        }
        $avg_progress = $total_active > 0 ? round($total_progress_sum / $total_active) : 0;

        // Penyesuaian label role pengguna
        $role_label = 'Peserta Mandiri';
        if ($userRole === 'mentor') {
            $role_label = 'Mentor Praktisi';
        } elseif ($userRole === 'peserta_b2b') {
            $role_label = 'Peserta B2B (' . ($instansiName ?? 'Institusi') . ')';
        }

        $data = [
            'title'            => 'Dasbor Pembelajaran - MUSLIM UPSKILL ACADEMY',
            'user_name'        => $userName,
            'user_role'        => $userRole,
            'role_label'       => $role_label,
            'user_email'       => $userEmail,
            'instansi_name'    => $instansiName,
            'enrolled_courses' => $enrolled_courses,
            'total_active'     => $total_active,
            'avg_progress'     => $avg_progress,
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Halaman Ruang Belajar Peserta (Tahap 18)
     * Menampilkan konten materi (Video YouTube atau Teks/PDF) dengan sistem pelacak progres
     *
     * @param int|string $sessionId
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function learn($sessionId)
    {
        $sessionId = (int) $sessionId;
        $lessonModel = new \App\Models\LessonModel();
        $session = $lessonModel->find($sessionId);

        if (!$session) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $courseModel = new \App\Models\CourseModel();
        $course = $courseModel->find((int) $session['course_id']);

        if (!$course) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Kelas tidak ditemukan.');
        }

        // Ambil data user aktif
        $userId    = (int) (session()->get('user_id') ?? 0);
        $userName  = session()->get('full_name') ?? 'Peserta';
        $userRole  = session()->get('role') ?? 'peserta_b2c';

        // Ambil daftar kurikulum seluruh sesi untuk kelas ini
        $allSessions = $lessonModel->getLessonsByCourse((int) $course['id']);

        // Ekstraksi Video ID jika tipe materi adalah video
        $videoId = '';
        if ($session['content_type'] === 'video') {
            $videoId = $this->extractYouTubeId($session['content_url_or_text'] ?? '');
        }

        // Cek jumlah soal kuis yang tersedia untuk sesi ini
        $quizModel = new \App\Models\QuizQuestionModel();
        $quizCount = $quizModel->countBySession($sessionId);

        // Cari sesi sebelum dan sesudahnya untuk navigasi
        $prevSession = null;
        $nextSession = null;
        $currentIndex = 1;
        foreach ($allSessions as $idx => $s) {
            if ((int) $s['id'] === $sessionId) {
                $currentIndex = $idx + 1;
                $prevSession = $allSessions[$idx - 1] ?? null;
                $nextSession = $allSessions[$idx + 1] ?? null;
                break;
            }
        }

        $data = [
            'title'        => 'Ruang Belajar: ' . esc($session['chapter_title']) . ' - ' . esc($course['title']),
            'session'      => $session,
            'course'       => $course,
            'allSessions'  => $allSessions,
            'videoId'      => $videoId,
            'quizCount'    => $quizCount,
            'currentIndex' => $currentIndex,
            'prevSession'  => $prevSession,
            'nextSession'  => $nextSession,
            'user_name'    => $userName,
            'user_role'    => $userRole,
        ];

        return view('dashboard/learn', $data);
    }

    /**
     * Helper untuk mengekstrak 11 karakter YouTube Video ID dari berbagai format URL
     * Mendukung: https://www.youtube.com/watch?v=xxx, https://youtu.be/xxx, embed/xxx, atau raw ID
     */
    private function extractYouTubeId(?string $url): string
    {
        if (empty($url)) {
            return '';
        }
        $url = trim($url);
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return '';
    }
}

