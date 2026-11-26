<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\TrialQuizAttemptModel;
use App\Models\TrialQuizModel;
use App\Models\TrialQuizQuestionModel;
use Config\Services;

class TrialQuiz extends BaseController
{
    private const IP_MAX_SUBMITS        = 15;
    private const IP_WINDOW_SECONDS     = 900;  // 15 menit
    private const GUEST_MAX_PER_HOUR    = 3;
    private const GUEST_COOLDOWN_SECOND = 15;

    protected TrialQuizModel $quizModel;
    protected TrialQuizQuestionModel $questionModel;
    protected TrialQuizAttemptModel $attemptModel;

    public function __construct()
    {
        $this->quizModel     = new TrialQuizModel();
        $this->questionModel = new TrialQuizQuestionModel();
        $this->attemptModel  = new TrialQuizAttemptModel();
    }

    /**
     * Entry langsung dari menu "Quiz" di navbar publik.
     * Membuka 1 Quiz Trial Utama tanpa perlu login.
     */
    public function index()
    {
        if (! \Config\Database::connect()->tableExists('trial_quizzes')) {
            return view('trial_quiz/empty', [
                'title' => 'Quiz Evaluasi - MUSLIM UPSKILL ACADEMY',
            ]);
        }

        $featured = $this->quizModel->getFeaturedTrial();

        if (! $featured) {
            return view('trial_quiz/empty', [
                'title' => 'Quiz Evaluasi - MUSLIM UPSKILL ACADEMY',
            ]);
        }

        return $this->renderPlayView($featured);
    }

    /**
     * Membuka halaman pengerjaan quiz tertentu berdasarkan slug.
     * Quiz dengan is_trial = 1 terbuka untuk tamu; is_trial = 0 wajib login.
     */
    public function play(string $slug)
    {
        if (! \Config\Database::connect()->tableExists('trial_quizzes')) {
            return redirect()->to(base_url('quiz'));
        }

        $quiz = $this->quizModel
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $quiz) {
            return redirect()->to(base_url('quiz'))->with('error', 'Quiz yang Anda cari tidak ditemukan atau belum dipublikasikan.');
        }

        $isLoggedIn = (bool) session()->get('isLoggedIn');
        if ((int) $quiz['is_trial'] !== 1 && ! $isLoggedIn) {
            session()->set('redirect_after_auth', 'quiz/' . $quiz['slug']);

            return redirect()->to(base_url('login'))
                ->with('info', 'Masuk atau daftar gratis terlebih dahulu untuk mengerjakan quiz "' . $quiz['title'] . '".');
        }

        return $this->renderPlayView($quiz);
    }

    /**
     * Memproses jawaban quiz (baik tamu untuk trial maupun member).
     */
    public function submit(string $slug)
    {
        if (! \Config\Database::connect()->tableExists('trial_quizzes')) {
            return redirect()->to(base_url('quiz'));
        }

        // 1. Proteksi Honeypot: tolak otomatis jika field jebakan terisi oleh bot
        if (trim((string) $this->request->getPost('website_url')) !== '') {
            log_message('warning', '[TrialQuiz] Honeypot triggered from ip={ip}', [
                'ip' => $this->request->getIPAddress(),
            ]);

            return redirect()->to(base_url('quiz'))->with('error', 'Permintaan tidak dapat diproses.');
        }

        $quiz = $this->quizModel
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $quiz) {
            return redirect()->to(base_url('quiz'))->with('error', 'Quiz tidak ditemukan.');
        }

        $isLoggedIn = (bool) session()->get('isLoggedIn');
        $userId     = $isLoggedIn ? (int) session()->get('user_id') : null;

        if ((int) $quiz['is_trial'] !== 1 && ! $isLoggedIn) {
            session()->set('redirect_after_auth', 'quiz/' . $quiz['slug']);

            return redirect()->to(base_url('login'))
                ->with('error', 'Silakan masuk terlebih dahulu untuk menyelesaikan quiz ini.');
        }

        // 2. Wajib memiliki penanda mulai sesi (mencegah POST mentah tanpa membuka halaman soal)
        $startKey       = 'trial_quiz_start_' . $quiz['id'];
        $startTimestamp = session()->get($startKey);
        if (empty($startTimestamp)) {
            return redirect()->to(base_url((int) $quiz['is_trial'] === 1 ? 'quiz' : 'quiz/' . $quiz['slug']))
                ->with('info', 'Sesi pengerjaan tidak ditemukan atau sudah berakhir. Silakan mulai ulang quiz.');
        }

        // 3. Proteksi IP Rate Limit (longgar untuk Wi-Fi bersama: maks 15 submit / 15 menit per IP)
        $throttler = Services::throttler();
        $ipKey     = 'trial_quiz_ip_' . md5((string) $this->request->getIPAddress());
        if ($throttler->check($ipKey, self::IP_MAX_SUBMITS, self::IP_WINDOW_SECONDS) === false) {
            log_message('warning', '[TrialQuiz] IP rate limit exceeded from ip={ip}', [
                'ip' => $this->request->getIPAddress(),
            ]);

            return redirect()->to(base_url('quiz'))
                ->with('error', 'Terlalu banyak pengiriman dari jaringan Anda. Silakan tunggu beberapa menit lalu coba kembali.');
        }

        // 4. Proteksi Session Limit & Cooldown khusus tamu (tanpa login)
        $now            = time();
        $guestHistory   = [];
        if (! $isLoggedIn) {
            $rawHistory   = session()->get('trial_quiz_submit_history');
            $guestHistory = is_array($rawHistory)
                ? array_values(array_filter($rawHistory, static fn ($ts): bool => is_numeric($ts) && ($now - (int) $ts) < 3600))
                : [];

            $lastSubmit = ! empty($guestHistory) ? (int) end($guestHistory) : 0;
            if ($lastSubmit > 0 && ($now - $lastSubmit) < self::GUEST_COOLDOWN_SECOND) {
                return redirect()->to(base_url('quiz'))
                    ->with('info', 'Mohon tunggu beberapa detik sebelum mengirim jawaban kembali.');
            }

            if (count($guestHistory) >= self::GUEST_MAX_PER_HOUR) {
                $pendingToken = (string) (session()->get('pending_trial_token') ?? '');
                $fallbackUrl  = $pendingToken !== '' ? base_url('quiz/result/' . $pendingToken) : base_url('register');

                return redirect()->to($fallbackUrl)
                    ->with('info', 'Batas percobaan tamu (3x per jam) telah tercapai. Daftar akun gratis untuk membuka pembahasan & mengikuti quiz lainnya.');
            }
        }

        // Validasi jawaban & hitung skor 100% di server
        $questions = $this->questionModel->getByQuiz((int) $quiz['id']);
        if (empty($questions)) {
            return redirect()->to(base_url('quiz'))->with('error', 'Quiz ini belum memiliki butir soal.');
        }

        $submittedAnswers = $this->request->getPost('answers');
        if (! is_array($submittedAnswers)) {
            $submittedAnswers = [];
        }

        $correctCount = 0;
        $cleanAnswers = [];

        foreach ($questions as $q) {
            $qId     = (int) $q['id'];
            $ans     = isset($submittedAnswers[$qId]) ? strtolower(trim((string) $submittedAnswers[$qId])) : '';
            $isValid = in_array($ans, ['a', 'b', 'c', 'd'], true);

            if ($isValid) {
                $cleanAnswers[$qId] = $ans;
                if ($ans === strtolower((string) $q['correct_answer'])) {
                    $correctCount++;
                }
            }
        }

        $totalQuestions = count($questions);
        $score          = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        $startKey        = 'trial_quiz_start_' . $quiz['id'];
        $startTimestamp  = session()->get($startKey);
        $durationSeconds = $startTimestamp ? max(1, time() - (int) $startTimestamp) : 1;
        $totalQuestions  = count($questions);
        $score           = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
        $durationSeconds = max(1, $now - (int) $startTimestamp);
        session()->remove($startKey);

        $token = bin2hex(random_bytes(16));

        $this->attemptModel->insert([
            'quiz_id'          => (int) $quiz['id'],
            'user_id'          => $userId,
            'attempt_token'    => $token,
            'score'            => $score,
            'correct_count'    => $correctCount,
            'total_questions'  => $totalQuestions,
            'duration_seconds' => $durationSeconds,
            'answers_json'     => json_encode($cleanAnswers, JSON_UNESCAPED_UNICODE),
            'claimed_at'       => $userId ? date('Y-m-d H:i:s') : null,
            'completed_at'     => date('Y-m-d H:i:s'),
        ]);

        if (! $userId) {
            $guestHistory[] = $now;
            session()->set('trial_quiz_submit_history', $guestHistory);
            session()->set('pending_trial_token', $token);
        }

        return redirect()->to(base_url('quiz/result/' . $token));
    }

    /**
     * Menampilkan halaman hasil evaluasi, teaser/pembahasan penuh, Share WA, quiz lain, & rekomendasi kelas.
     */
    public function result(string $token)
    {
        $attempt = $this->attemptModel->findByToken($token);
        if (! $attempt) {
            return redirect()->to(base_url('quiz'))->with('error', 'Hasil quiz tidak ditemukan.');
        }

        $quiz = $this->quizModel->find((int) $attempt['quiz_id']);
        if (! $quiz) {
            return redirect()->to(base_url('quiz'))->with('error', 'Data quiz sudah tidak tersedia.');
        }

        $isLoggedIn = (bool) session()->get('isLoggedIn');
        $userId     = $isLoggedIn ? (int) session()->get('user_id') : 0;
        $userRole   = (string) (session()->get('role') ?? '');

        // Auto-claim jika user login dan membuka hasil trial miliknya yang belum diklaim
        if ($isLoggedIn && $attempt['user_id'] === null && session()->get('pending_trial_token') === $token) {
            $this->attemptModel->claimByToken($token, $userId);
            session()->remove('pending_trial_token');
            $attempt = $this->attemptModel->findByToken($token);
        }

        // Lindungi hasil milik peserta lain
        if ($attempt['user_id'] !== null && (int) $attempt['user_id'] !== $userId && $userRole !== 'super_admin') {
            return redirect()->to(base_url('quiz/' . $quiz['slug']))
                ->with('info', 'Hasil tersebut milik peserta lain. Yuk coba kerjakan quiznya sendiri!');
        }

        $isUnlocked = $isLoggedIn && (
            ($attempt['user_id'] !== null && (int) $attempt['user_id'] === $userId)
            || $userRole === 'super_admin'
        );

        // Pastikan pending_trial_token tetap tercatat untuk tamu yang membuka ulang halaman hasil
        if (! $isLoggedIn && $attempt['user_id'] === null) {
            session()->set('pending_trial_token', $token);
        }

        $questions    = $this->questionModel->getByQuiz((int) $quiz['id']);
        $answers      = json_decode($attempt['answers_json'] ?? '{}', true) ?: [];
        $otherQuizzes = $this->quizModel->getOtherPublishedQuizzes((int) $quiz['id']);

        // Ambil seluruh kelas published yang ditambahkan di Dashboard Admin untuk "Tantangan Quiz Lainnya"
        $courseModel      = new CourseModel();
        $publishedCourses = $courseModel->where('status', 'published')
            ->orderBy('id', 'DESC')
            ->findAll();

        $recommendedCourse = null;
        if (! empty($quiz['recommended_course_id'])) {
            foreach ($publishedCourses as $pc) {
                if ((int) $pc['id'] === (int) $quiz['recommended_course_id']) {
                    $recommendedCourse = $pc;
                    break;
                }
            }
        }
        if ($recommendedCourse === null && ! empty($publishedCourses)) {
            $recommendedCourse = $publishedCourses[0];
        }

        // Skala predikat capaian (Mumtaz, Jayyid Jiddan, Jayyid, Maqbul, Perlu Pendalaman)
        $scoreVal = (float) $attempt['score'];
        if ($scoreVal >= 90) {
            $predicate = [
                'label'   => 'Mumtaz',
                'summary' => 'Pemahaman Anda sangat baik dan menyeluruh. Pertahankan dengan memperdalam materi lanjutan.',
                'tone'    => 'emerald',
            ];
        } elseif ($scoreVal >= 75) {
            $predicate = [
                'label'   => 'Jayyid Jiddan',
                'summary' => 'Sebagian besar konsep sudah Anda kuasai dengan sangat baik. Tinggal menyempurnakan beberapa detail.',
                'tone'    => 'blue',
            ];
        } elseif ($scoreVal >= 60) {
            $predicate = [
                'label'   => 'Jayyid',
                'summary' => 'Sebagian besar konsep sudah Anda kuasai, namun masih ada detail penting yang perlu diluruskan.',
                'tone'    => 'indigo',
            ];
        } elseif ($scoreVal >= 40) {
            $predicate = [
                'label'   => 'Maqbul',
                'summary' => 'Dasar pemahaman sudah terbentuk. Pelajari pembahasannya agar penguasaan materi semakin mantap.',
                'tone'    => 'amber',
            ];
        } else {
            $predicate = [
                'label'   => 'Perlu Pendalaman',
                'summary' => 'Langkah awal yang bagus untuk mengetahui bagian mana yang perlu dipelajari lebih runut.',
                'tone'    => 'rose',
            ];
        }

        // Tautan Share WhatsApp selalu mengarah ke halaman mulai quiz (bukan hasil pribadi)
        $shareTargetUrl = (int) $quiz['is_trial'] === 1
            ? base_url('quiz')
            : base_url('quiz/' . $quiz['slug']);

        $shareMessage = sprintf(
            'Alhamdulillah, saya baru saja mencoba "%s" di Muslim Upskill Academy dan mendapat skor %d/%d benar (%d%% - %s). Yuk uji pemahamanmu juga di sini: %s',
            $quiz['title'],
            (int) $attempt['correct_count'],
            (int) $attempt['total_questions'],
            (int) round($scoreVal),
            $predicate['label'],
            $shareTargetUrl
        );

        $whatsappShareUrl = 'https://wa.me/?text=' . rawurlencode($shareMessage);

        return view('trial_quiz/result', [
            'title'              => 'Hasil Quiz: ' . $quiz['title'] . ' - MUSLIM UPSKILL ACADEMY',
            'quiz'               => $quiz,
            'attempt'            => $attempt,
            'questions'          => $questions,
            'answers'            => $answers,
            'is_unlocked'        => $isUnlocked,
            'is_logged_in'       => $isLoggedIn,
            'predicate'          => $predicate,
            'other_quizzes'      => $otherQuizzes,
            'published_courses'  => $publishedCourses,
            'recommended_course' => $recommendedCourse,
            'whatsapp_share_url' => $whatsappShareUrl,
            'retry_url'          => (int) $quiz['is_trial'] === 1 ? base_url('quiz') : base_url('quiz/' . $quiz['slug']),
        ]);
    }

    /**
     * Merender tampilan pengerjaan quiz ringan.
     */
    private function renderPlayView(array $quiz)
    {
        $questions = $this->questionModel->getByQuiz((int) $quiz['id']);
        // Hanya kirim teks soal & pilihan A-D ke view (tanpa correct_answer & explanation)
        $questions = $this->questionModel->getPublicByQuiz((int) $quiz['id']);

        if (empty($questions)) {
            return view('trial_quiz/empty', [
                'title' => 'Quiz Belum Tersedia - MUSLIM UPSKILL ACADEMY',
            ]);
        }

        session()->set('trial_quiz_start_' . $quiz['id'], time());

        return view('trial_quiz/play', [
            'title'     => $quiz['title'] . ' - MUSLIM UPSKILL ACADEMY',
            'quiz'      => $quiz,
            'questions' => $questions,
        ]);
    }
}

