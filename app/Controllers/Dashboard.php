<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\QuizQuestionModel;
use App\Models\SessionProgressModel;
use App\Models\UserModel;

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

        // Ambil seluruh kelas yang telah dipublikasikan oleh Admin dari katalog kursus
        $courseModel     = new CourseModel();
        $enrollmentModel = new EnrollmentModel();

        $allCourses = $courseModel->select('courses.*, users.full_name as mentor_name')
                                  ->join('users', 'users.id = courses.mentor_id', 'left')
                                  ->where('courses.status', 'published')
                                  ->orderBy('courses.id', 'DESC')
                                  ->findAll();

        // Ambil riwayat pendaftaran aktif user jika ada
        $userEnrollments = [];
        if ($userId > 0) {
            $enrolled = $enrollmentModel->where('user_id', $userId)->findAll();
            foreach ($enrolled as $en) {
                $userEnrollments[(int) $en['course_id']] = $en;
            }
        }

        // Gabungkan status pendaftaran dan progres untuk setiap kelas katalog
        $enrolled_courses   = [];
        $total_progress_sum = 0;
        $total_enrolled     = 0;

        foreach ($allCourses as $c) {
            $cid = (int) $c['id'];
            if (isset($userEnrollments[$cid])) {
                $c['is_enrolled']          = true;
                $c['progress_percentage']  = (int) ($userEnrollments[$cid]['progress_percentage'] ?? 0);
                $c['enrollment_status']    = $userEnrollments[$cid]['status'] ?? 'active';
                $total_progress_sum       += $c['progress_percentage'];
                $total_enrolled++;
            } else {
                $c['is_enrolled']          = false;
                $c['progress_percentage']  = 0;
                $c['enrollment_status']    = 'available';
            }
            $enrolled_courses[] = $c;
        }

        // Hitung statistik
        $total_active = count($enrolled_courses);
        $avg_progress = $total_enrolled > 0 ? round($total_progress_sum / $total_enrolled) : 0;

        // Penyesuaian label role pengguna
        $role_label = 'Peserta Mandiri';
        if ($userRole === 'mentor') {
            $role_label = 'Mentor Praktisi';
        } elseif ($userRole === 'peserta_b2b') {
            $role_label = 'Peserta B2B (' . ($instansiName ?? 'Institusi') . ')';
        }

        // Hitung statistik kuis (Tahap 21)
        $courseIds = array_column($allCourses, 'id');
        $allSessionIds = [];
        if (!empty($courseIds)) {
            $lessonModel = new \App\Models\LessonModel();
            $allSessionsInCourses = $lessonModel->whereIn('course_id', $courseIds)->findAll();
            $allSessionIds = array_column($allSessionsInCourses, 'id');
        }

        // Cari sesi mana saja yang memiliki butir soal kuis
        $quizModel = new QuizQuestionModel();
        $sessionsWithQuizzes = [];
        if (!empty($allSessionIds)) {
            $quizQuestions = $quizModel->select('DISTINCT(session_id) as session_id')->whereIn('session_id', $allSessionIds)->findAll();
            $sessionsWithQuizzes = array_column($quizQuestions, 'session_id');
        }
        $total_available_quizzes = count($sessionsWithQuizzes);

        // Kuis yang sudah dikerjakan oleh user
        $progressModel = new SessionProgressModel();
        $quizzes_completed = 0;
        if ($userId > 0 && !empty($sessionsWithQuizzes)) {
            $completedRows = $progressModel->where('user_id', $userId)
                                           ->where('quiz_completed', 1)
                                           ->whereIn('session_id', $sessionsWithQuizzes)
                                           ->findAll();
            $quizzes_completed = count($completedRows);
        }
        $quizzes_pending = max(0, $total_available_quizzes - $quizzes_completed);

        $data = [
            'title'             => 'Dasbor Pembelajaran - MUSLIM UPSKILL ACADEMY',
            'user_name'         => $userName,
            'user_role'         => $userRole,
            'role_label'        => $role_label,
            'user_email'        => $userEmail,
            'instansi_name'     => $instansiName,
            'enrolled_courses'  => $enrolled_courses,
            'total_active'      => $total_active,
            'avg_progress'      => $avg_progress,
            'quizzes_completed' => $quizzes_completed,
            'quizzes_pending'   => $quizzes_pending,
            'total_quizzes'     => $total_available_quizzes,
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Halaman Pengaturan Akun Peserta (Tahap 21)
     */
    public function account()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('login'))->with('error', 'Sesi Anda telah berakhir.');
        }

        $userName     = $user['full_name'] ?? session()->get('full_name') ?? 'Pengguna';
        $userRole     = $user['role'] ?? session()->get('role') ?? 'peserta_b2c';
        $userEmail    = $user['email'] ?? session()->get('email') ?? '';
        $instansiName = $user['instansi_name'] ?? session()->get('instansi_name') ?? null;

        $role_label = 'Peserta Mandiri';
        if ($userRole === 'mentor') {
            $role_label = 'Mentor Praktisi';
        } elseif ($userRole === 'peserta_b2b') {
            $role_label = 'Peserta B2B (' . ($instansiName ?? 'Institusi') . ')';
        }

        $data = [
            'title'         => 'Pengaturan Akun - MUSLIM UPSKILL ACADEMY',
            'user'          => $user,
            'user_name'     => $userName,
            'user_role'     => $userRole,
            'role_label'    => $role_label,
            'user_email'    => $userEmail,
            'instansi_name' => $instansiName,
        ];

        return view('dashboard/account', $data);
    }

    /**
     * Memproses pembaruan nama profil & password peserta (Tahap 21)
     */
    public function updateAccount()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->to(base_url('login'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $fullName = trim((string) $this->request->getPost('full_name'));
        $password = (string) $this->request->getPost('password');

        if (empty($fullName)) {
            return redirect()->back()->with('error', 'Nama pengguna tidak boleh kosong.')->withInput();
        }

        $updateData = [
            'full_name' => $fullName,
        ];

        // Jika input password diisi, lakukan hashing dan update
        if (!empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->back()->with('error', 'Password baru minimal harus 6 karakter.')->withInput();
            }
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->update($userId, $updateData);

        // Perbarui session nama pengguna
        session()->set('full_name', $fullName);

        return redirect()->to(base_url('dashboard/account'))->with('success', 'Data profil akun Anda berhasil diperbarui!');
    }

    /**
     * Halaman Detail Kelas & Accordion Sesi Kurikulum (Tahap 19)
     *
     * @param int|string $id
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function course($id)
    {
        $courseId = (int) $id;
        $courseModel = new CourseModel();

        $course = $courseModel->select('courses.*, users.full_name as mentor_name')
                              ->join('users', 'users.id = courses.mentor_id', 'left')
                              ->find($courseId);

        if (!$course) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Program kelas tidak ditemukan.');
        }

        // Ambil data user aktif
        $userId       = (int) (session()->get('user_id') ?? 0);
        $userName     = session()->get('full_name') ?? 'Pengguna';
        $userRole     = session()->get('role') ?? 'peserta_b2c';
        $userEmail    = session()->get('email') ?? '';
        $instansiName = session()->get('instansi_name') ?? null;

        // Auto-enroll user jika belum terdaftar
        $enrollmentModel = new EnrollmentModel();
        if ($userId > 0) {
            $enrollmentModel->enrollUser($userId, $courseId);
        }

        // Ambil semua data sessions yang terkait dengan kelas tersebut
        $lessonModel = new \App\Models\LessonModel();
        $sessions    = $lessonModel->getLessonsByCourse($courseId);

        // Kumpulkan ID sesi
        $sessionIds = array_column($sessions, 'id');

        // Query pengecekan ke tabel pelacakan progres (session_progress) untuk mengetahui status 100% tiap sesi
        $progressModel   = new SessionProgressModel();
        $progress_status = $progressModel->getProgressMap($userId, $sessionIds);

        // Tambahkan info kuis dan tipe materi per sesi
        $quizModel = new \App\Models\QuizQuestionModel();
        foreach ($sessions as &$s) {
            $s['quiz_count'] = $quizModel->countBySession((int) $s['id']);
        }
        unset($s);

        // Hitung persentase progres kelas
        $totalSessions     = count($sessions);
        $completedSessions = 0;
        foreach ($sessions as $s) {
            if (!empty($progress_status[(int) $s['id']])) {
                $completedSessions++;
            }
        }
        $courseProgress = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0;

        // Update progres di enrollments
        if ($userId > 0) {
            $enrollment = $enrollmentModel->where('user_id', $userId)->where('course_id', $courseId)->first();
            if ($enrollment) {
                $enrollmentModel->update($enrollment['id'], ['progress_percentage' => $courseProgress]);
            }
        }

        // Penyesuaian label role
        $role_label = 'Peserta Mandiri';
        if ($userRole === 'mentor') {
            $role_label = 'Mentor Praktisi';
        } elseif ($userRole === 'peserta_b2b') {
            $role_label = 'Peserta B2B (' . ($instansiName ?? 'Institusi') . ')';
        }

        $data = [
            'title'             => 'Detail Program: ' . esc($course['title']) . ' - MUSLIM UPSKILL ACADEMY',
            'course'            => $course,
            'sessions'          => $sessions,
            'progress_status'   => $progress_status,
            'courseProgress'    => $courseProgress,
            'completedSessions' => $completedSessions,
            'totalSessions'     => $totalSessions,
            'user_name'         => $userName,
            'user_role'         => $userRole,
            'role_label'        => $role_label,
            'user_email'        => $userEmail,
        ];

        return view('dashboard/course', $data);
    }

    /**
     * Menandai progres sesi sebagai selesai 100%
     */
    public function completeSession($sessionId)
    {
        $userId    = (int) (session()->get('user_id') ?? 0);
        $sessionId = (int) $sessionId;

        if ($userId > 0 && $sessionId > 0) {
            $progressModel = new SessionProgressModel();
            $progressModel->markCompleted($userId, $sessionId);

            $lessonModel = new \App\Models\LessonModel();
            $session = $lessonModel->find($sessionId);
            if ($session) {
                $courseId    = (int) $session['course_id'];
                $allSessions = $lessonModel->getLessonsByCourse($courseId);
                $allIds      = array_column($allSessions, 'id');
                $progressMap = $progressModel->getProgressMap($userId, $allIds);
                $completed   = count(array_filter($progressMap));
                $pct         = count($allIds) > 0 ? round(($completed / count($allIds)) * 100) : 0;

                $enrollmentModel = new EnrollmentModel();
                $enr = $enrollmentModel->where('user_id', $userId)->where('course_id', $courseId)->first();
                if ($enr) {
                    $enrollmentModel->update($enr['id'], ['progress_percentage' => $pct]);
                }
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Materi selesai 100%']);
            }
        }

        return redirect()->back();
    }

    /**
     * Mengakses materi eksternal & menandai progres sesi 100% di backend (Tahap 20)
     *
     * @param int|string $sessionId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function access_material($sessionId)
    {
        $sessionId = (int) $sessionId;
        $userId    = (int) (session()->get('user_id') ?? 0);

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $lessonModel = new \App\Models\LessonModel();
        $session = $lessonModel->find($sessionId);

        if (!$session) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Sesi materi tidak ditemukan.');
        }

        // 1. Simpan status selesai 100% ke tabel session_progress untuk user ini
        $progressModel = new SessionProgressModel();
        $progressModel->markCompleted($userId, $sessionId);

        // 2. Perbarui progres persentase kelas di tabel enrollments
        $courseId    = (int) $session['course_id'];
        $allSessions = $lessonModel->getLessonsByCourse($courseId);
        $allIds      = array_column($allSessions, 'id');
        $progressMap = $progressModel->getProgressMap($userId, $allIds);
        $completed   = count(array_filter($progressMap));
        $pct         = count($allIds) > 0 ? round(($completed / count($allIds)) * 100) : 0;

        $enrollmentModel = new EnrollmentModel();
        $enr = $enrollmentModel->where('user_id', $userId)->where('course_id', $courseId)->first();
        if ($enr) {
            $enrollmentModel->update($enr['id'], ['progress_percentage' => $pct]);
        }

        // 3. Ambil URL materi dan lakukan redirect langsung
        $targetUrl = trim($session['content_url_or_text'] ?? '');

        // Jaring pengaman jika URL kosong atau bukan link eksternal yang valid
        if (empty($targetUrl) || (!str_starts_with($targetUrl, 'http://') && !str_starts_with($targetUrl, 'https://'))) {
            return redirect()->to(base_url('dashboard/learn/' . $sessionId));
        }

        return redirect()->to($targetUrl);
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

        // Cek status apakah sesi ini sudah diselesaikan sebelumnya
        $progressModel = new SessionProgressModel();
        $isCompleted   = $progressModel->isCompleted($userId, $sessionId);

        // Ekstraksi Embed URL untuk Dokumen / PDF (Google Drive preview)
        $embedUrl = trim($session['content_url_or_text'] ?? '');
        if (!empty($embedUrl) && strpos($embedUrl, 'drive.google.com') !== false) {
            $embedUrl = preg_replace('/\/view(\?usp=[^\s&]+)?.*$/', '/preview', $embedUrl);
            if (strpos($embedUrl, '/preview') === false && strpos($embedUrl, '/d/') !== false) {
                $embedUrl = rtrim($embedUrl, '/') . '/preview';
            }
        }

        $data = [
            'title'        => 'Ruang Belajar: ' . esc($session['chapter_title']) . ' - ' . esc($course['title']),
            'session'      => $session,
            'course'       => $course,
            'allSessions'  => $allSessions,
            'videoId'      => $videoId,
            'embedUrl'     => $embedUrl,
            'quizCount'    => $quizCount,
            'currentIndex' => $currentIndex,
            'prevSession'  => $prevSession,
            'nextSession'  => $nextSession,
            'isCompleted'  => $isCompleted,
            'user_name'    => $userName,
            'user_role'    => $userRole,
        ];

        return view('dashboard/learn', $data);
    }

    /**
     * Endpoint AJAX untuk menandai materi sesi selesai 100% dan membuka kunci kuis (Tahap 22)
     *
     * @param int|string $sessionId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function mark_completed($sessionId)
    {
        $sessionId = (int) $sessionId;
        $userId    = (int) (session()->get('user_id') ?? 0);

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Sesi autentikasi telah berakhir. Silakan login kembali.',
            ]);
        }

        // Method ini hanya menerima request AJAX (POST)
        if (!$this->request->isAJAX() || strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Permintaan tidak valid, wajib menggunakan AJAX POST.',
            ]);
        }

        $lessonModel = new \App\Models\LessonModel();
        $session = $lessonModel->find($sessionId);
        if (!$session) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Sesi pembelajaran tidak ditemukan.',
            ]);
        }

        // 1. Update tabel session_progress untuk user_id ini menjadi 100%
        $progressModel = new SessionProgressModel();
        $progressModel->markCompleted($userId, $sessionId);

        // 2. Perbarui progres persentase kelas di tabel enrollments
        $courseId    = (int) $session['course_id'];
        $allSessions = $lessonModel->getLessonsByCourse($courseId);
        $allIds      = array_column($allSessions, 'id');
        $progressMap = $progressModel->getProgressMap($userId, $allIds);
        $completed   = count(array_filter($progressMap));
        $pct         = count($allIds) > 0 ? round(($completed / count($allIds)) * 100) : 0;

        $enrollmentModel = new EnrollmentModel();
        $enr = $enrollmentModel->where('user_id', $userId)->where('course_id', $courseId)->first();
        if ($enr) {
            $enrollmentModel->update($enr['id'], ['progress_percentage' => $pct]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kuis dibuka',
        ]);
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

