<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\TrialQuizAttemptModel;
use App\Models\TrialQuizModel;
use App\Models\TrialQuizQuestionModel;
use Config\Database;

class TrialQuiz extends BaseController
{
    protected TrialQuizModel $quizModel;
    protected TrialQuizQuestionModel $questionModel;
    protected TrialQuizAttemptModel $attemptModel;
    protected CourseModel $courseModel;

    public function __construct()
    {
        $this->quizModel     = new TrialQuizModel();
        $this->questionModel = new TrialQuizQuestionModel();
        $this->attemptModel  = new TrialQuizAttemptModel();
        $this->courseModel   = new CourseModel();
    }

    /**
     * Daftar seluruh Trial Quiz & Quiz Mandiri.
     */
    public function index()
    {
        if (! \Config\Database::connect()->tableExists('trial_quizzes')) {
            return redirect()->to(base_url('admin/maintenance'))
                ->with('warning', 'Tabel Trial Quiz belum tersedia di database. Silakan jalankan Database Migration terlebih dahulu.');
        }

        $quizzes = $this->quizModel->getAllWithStats();

        return view('admin/trial_quizzes/index', [
            'title'       => 'Kelola Trial Quiz - MUSLIM UPSKILL ACADEMY',
            'quizzes'     => $quizzes,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ]);
    }

    /**
     * Formulir buat quiz baru.
     */
    public function create(): string
    {
        $courses = $this->courseModel->orderBy('title', 'ASC')->findAll();

        return view('admin/trial_quizzes/form', [
            'title'       => 'Tambah Trial Quiz Baru - MUSLIM UPSKILL ACADEMY',
            'quiz'        => null,
            'courses'     => $courses,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ]);
    }

    /**
     * Menyimpan quiz baru.
     */
    public function store()
    {
        $rules = [
            'title'                 => 'required|min_length[4]|max_length[200]',
            'topic_label'           => 'permit_empty|max_length[80]',
            'description'           => 'permit_empty',
            'status'                => 'required|in_list[draft,published]',
            'duration_minutes'      => 'permit_empty|is_natural',
            'recommended_course_id' => 'permit_empty|is_natural_no_zero',
        ];

        $messages = [
            'title' => [
                'required'   => 'Judul quiz wajib diisi.',
                'min_length' => 'Judul quiz minimal terdiri dari 4 karakter.',
            ],
            'status' => [
                'required' => 'Status publikasi wajib dipilih.',
                'in_list'  => 'Status harus berupa draft atau published.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isTrial             = $this->request->getPost('is_trial') ? 1 : 0;
        $recommendedCourseId = (int) $this->request->getPost('recommended_course_id');

        $data = [
            'title'                 => trim((string) $this->request->getPost('title')),
            'topic_label'           => trim((string) $this->request->getPost('topic_label')) ?: null,
            'description'           => trim((string) $this->request->getPost('description')) ?: null,
            'is_trial'              => $isTrial,
            'status'                => (string) $this->request->getPost('status'),
            'duration_minutes'      => max(0, (int) $this->request->getPost('duration_minutes')),
            'recommended_course_id' => $recommendedCourseId > 0 ? $recommendedCourseId : null,
            'created_by'            => (int) (session()->get('user_id') ?? 1),
        ];

        if ($isTrial === 1) {
            $this->quizModel->clearOtherTrials();
        }

        $this->quizModel->insert($data);
        $newId = (int) $this->quizModel->getInsertID();

        return redirect()->to(base_url('admin/trial-quizzes/' . $newId . '/questions'))
            ->with('success', 'Quiz baru berhasil dibuat! Silakan tambahkan butir soal dan pembahasannya.');
    }

    /**
     * Formulir edit metadata quiz.
     */
    public function edit(int $id)
    {
        $quiz = $this->quizModel->find($id);
        if (! $quiz) {
            return redirect()->to(base_url('admin/trial-quizzes'))->with('error', 'Data quiz tidak ditemukan.');
        }

        $courses = $this->courseModel->orderBy('title', 'ASC')->findAll();

        return view('admin/trial_quizzes/form', [
            'title'       => 'Edit Quiz: ' . $quiz['title'] . ' - MUSLIM UPSKILL ACADEMY',
            'quiz'        => $quiz,
            'courses'     => $courses,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ]);
    }

    /**
     * Memperbarui metadata quiz.
     */
    public function update(int $id)
    {
        $quiz = $this->quizModel->find($id);
        if (! $quiz) {
            return redirect()->to(base_url('admin/trial-quizzes'))->with('error', 'Data quiz tidak ditemukan.');
        }

        $rules = [
            'title'                 => 'required|min_length[4]|max_length[200]',
            'topic_label'           => 'permit_empty|max_length[80]',
            'description'           => 'permit_empty',
            'status'                => 'required|in_list[draft,published]',
            'duration_minutes'      => 'permit_empty|is_natural',
            'recommended_course_id' => 'permit_empty|is_natural_no_zero',
        ];

        $messages = [
            'title' => [
                'required'   => 'Judul quiz wajib diisi.',
                'min_length' => 'Judul quiz minimal terdiri dari 4 karakter.',
            ],
            'status' => [
                'required' => 'Status publikasi wajib dipilih.',
                'in_list'  => 'Status harus berupa draft atau published.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isTrial             = $this->request->getPost('is_trial') ? 1 : 0;
        $recommendedCourseId = (int) $this->request->getPost('recommended_course_id');

        if ($isTrial === 1) {
            $this->quizModel->clearOtherTrials($id);
        }

        $data = [
            'title'                 => trim((string) $this->request->getPost('title')),
            'topic_label'           => trim((string) $this->request->getPost('topic_label')) ?: null,
            'description'           => trim((string) $this->request->getPost('description')) ?: null,
            'is_trial'              => $isTrial,
            'status'                => (string) $this->request->getPost('status'),
            'duration_minutes'      => max(0, (int) $this->request->getPost('duration_minutes')),
            'recommended_course_id' => $recommendedCourseId > 0 ? $recommendedCourseId : null,
        ];

        $this->quizModel->update($id, $data);

        return redirect()->to(base_url('admin/trial-quizzes'))
            ->with('success', 'Pengaturan quiz "' . $data['title'] . '" berhasil diperbarui.');
    }

    /**
     * Menghapus quiz beserta seluruh soal & riwayatnya (ON DELETE CASCADE).
     */
    public function delete(int $id)
    {
        $quiz = $this->quizModel->find($id);
        if (! $quiz) {
            return redirect()->to(base_url('admin/trial-quizzes'))->with('error', 'Quiz tidak ditemukan.');
        }

        $this->quizModel->delete($id);

        return redirect()->to(base_url('admin/trial-quizzes'))
            ->with('success', 'Quiz "' . $quiz['title'] . '" berhasil dihapus.');
    }

    /**
     * Halaman kelola butir soal & pembahasan untuk 1 quiz.
     */
    public function questions(int $quizId)
    {
        $quiz = $this->quizModel->find($quizId);
        if (! $quiz) {
            return redirect()->to(base_url('admin/trial-quizzes'))->with('error', 'Quiz tidak ditemukan.');
        }

        $questions = $this->questionModel->getByQuiz($quizId);
        $nextOrder = $this->questionModel->getNextOrderIndex($quizId);

        return view('admin/trial_quizzes/questions', [
            'title'       => 'Kelola Soal: ' . $quiz['title'] . ' - MUSLIM UPSKILL ACADEMY',
            'quiz'        => $quiz,
            'questions'   => $questions,
            'nextOrder'   => $nextOrder,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ]);
    }

    /**
     * Menyimpan butir soal PG + pembahasan baru.
     */
    public function storeQuestion(int $quizId)
    {
        $quiz = $this->quizModel->find($quizId);
        if (! $quiz) {
            return redirect()->to(base_url('admin/trial-quizzes'))->with('error', 'Quiz tidak ditemukan.');
        }

        $rules = [
            'question_text'  => 'required|min_length[5]',
            'option_a'       => 'required',
            'option_b'       => 'required',
            'option_c'       => 'required',
            'option_d'       => 'required',
            'correct_answer' => 'required|in_list[a,b,c,d]',
            'explanation'    => 'permit_empty',
            'order_index'    => 'permit_empty|is_natural_no_zero',
        ];

        $messages = [
            'question_text' => [
                'required'   => 'Teks pertanyaan wajib diisi.',
                'min_length' => 'Teks pertanyaan minimal 5 karakter.',
            ],
            'option_a'       => ['required' => 'Pilihan A wajib diisi.'],
            'option_b'       => ['required' => 'Pilihan B wajib diisi.'],
            'option_c'       => ['required' => 'Pilihan C wajib diisi.'],
            'option_d'       => ['required' => 'Pilihan D wajib diisi.'],
            'correct_answer' => [
                'required' => 'Kunci jawaban wajib dipilih.',
                'in_list'  => 'Kunci jawaban harus A, B, C, atau D.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $orderIndex = (int) $this->request->getPost('order_index');
        if ($orderIndex <= 0) {
            $orderIndex = $this->questionModel->getNextOrderIndex($quizId);
        }

        $this->questionModel->insert([
            'quiz_id'        => $quizId,
            'question_text'  => trim((string) $this->request->getPost('question_text')),
            'option_a'       => trim((string) $this->request->getPost('option_a')),
            'option_b'       => trim((string) $this->request->getPost('option_b')),
            'option_c'       => trim((string) $this->request->getPost('option_c')),
            'option_d'       => trim((string) $this->request->getPost('option_d')),
            'correct_answer' => (string) $this->request->getPost('correct_answer'),
            'explanation'    => trim((string) $this->request->getPost('explanation')) ?: null,
            'order_index'    => $orderIndex,
        ]);

        return redirect()->to(base_url('admin/trial-quizzes/' . $quizId . '/questions'))
            ->with('success', 'Butir soal berhasil ditambahkan.');
    }

    /**
     * Memperbarui butir soal & pembahasan.
     */
    public function updateQuestion(int $id)
    {
        $question = $this->questionModel->find($id);
        if (! $question) {
            return redirect()->back()->with('error', 'Butir soal tidak ditemukan.');
        }

        $quizId = (int) $question['quiz_id'];

        $rules = [
            'question_text'  => 'required|min_length[5]',
            'option_a'       => 'required',
            'option_b'       => 'required',
            'option_c'       => 'required',
            'option_d'       => 'required',
            'correct_answer' => 'required|in_list[a,b,c,d]',
            'explanation'    => 'permit_empty',
            'order_index'    => 'permit_empty|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $orderIndex = (int) $this->request->getPost('order_index');

        $this->questionModel->update($id, [
            'question_text'  => trim((string) $this->request->getPost('question_text')),
            'option_a'       => trim((string) $this->request->getPost('option_a')),
            'option_b'       => trim((string) $this->request->getPost('option_b')),
            'option_c'       => trim((string) $this->request->getPost('option_c')),
            'option_d'       => trim((string) $this->request->getPost('option_d')),
            'correct_answer' => (string) $this->request->getPost('correct_answer'),
            'explanation'    => trim((string) $this->request->getPost('explanation')) ?: null,
            'order_index'    => $orderIndex > 0 ? $orderIndex : (int) $question['order_index'],
        ]);

        return redirect()->to(base_url('admin/trial-quizzes/' . $quizId . '/questions'))
            ->with('success', 'Butir soal berhasil diperbarui.');
    }

    /**
     * Menghapus butir soal.
     */
    public function deleteQuestion(int $id)
    {
        $question = $this->questionModel->find($id);
        if (! $question) {
            return redirect()->back()->with('error', 'Butir soal tidak ditemukan.');
        }

        $quizId = (int) $question['quiz_id'];
        $this->questionModel->delete($id);

        return redirect()->to(base_url('admin/trial-quizzes/' . $quizId . '/questions'))
            ->with('success', 'Butir soal berhasil dihapus.');
    }

    /**
     * Menampilkan riwayat pengerjaan (member/terkonversi vs tamu anonim) untuk 1 quiz.
     */
    public function attempts(int $quizId)
    {
        if (! Database::connect()->tableExists('trial_quizzes')) {
            return redirect()->to(base_url('admin/maintenance'))
                ->with('warning', 'Tabel Trial Quiz belum tersedia di database.');
        }

        $quiz = $this->quizModel->find($quizId);
        if (! $quiz) {
            return redirect()->to(base_url('admin/trial-quizzes'))->with('error', 'Quiz tidak ditemukan.');
        }

        $filter = strtolower(trim((string) ($this->request->getGet('filter') ?? 'all')));
        if (! in_array($filter, ['all', 'registered', 'guest'], true)) {
            $filter = 'all';
        }
        $search = trim((string) ($this->request->getGet('search') ?? ''));

        $db = Database::connect();

        // Statistik ringkas (selalu dari total quiz ini)
        $statsRow = $db->table('trial_quiz_attempts')
            ->select('
                COUNT(*) as total_attempts,
                SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as registered_count,
                SUM(CASE WHEN user_id IS NULL THEN 1 ELSE 0 END) as guest_count,
                AVG(score) as avg_score
            ')
            ->where('quiz_id', $quizId)
            ->get()
            ->getRowArray();

        $totalAttempts   = (int) ($statsRow['total_attempts'] ?? 0);
        $registeredCount = (int) ($statsRow['registered_count'] ?? 0);
        $guestCount      = (int) ($statsRow['guest_count'] ?? 0);
        $avgScore        = $totalAttempts > 0 ? round((float) ($statsRow['avg_score'] ?? 0), 1) : 0;
        $conversionRate  = $totalAttempts > 0 ? (int) round(($registeredCount / $totalAttempts) * 100) : 0;

        // Query daftar riwayat dengan filter & pencarian
        $builder = $db->table('trial_quiz_attempts a')
            ->select('a.*, u.full_name as user_name, u.email as user_email, u.role as user_role')
            ->join('users u', 'u.id = a.user_id', 'left')
            ->where('a.quiz_id', $quizId);

        if ($filter === 'registered') {
            $builder->where('a.user_id IS NOT NULL', null, false);
        } elseif ($filter === 'guest') {
            $builder->where('a.user_id IS NULL', null, false);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('u.full_name', $search)
                ->orLike('u.email', $search)
                ->orLike('a.attempt_token', $search)
                ->groupEnd();
        }

        $attempts = $builder
            ->orderBy('a.completed_at', 'DESC')
            ->orderBy('a.id', 'DESC')
            ->get()
            ->getResultArray();

        // Tambahkan label predikat HSI per baris
        foreach ($attempts as &$row) {
            $row['predicate'] = $this->resolveHsiPredicate((int) round((float) ($row['score'] ?? 0)));
        }
        unset($row);

        return view('admin/trial_quizzes/attempts', [
            'title'            => 'Riwayat Pengerjaan: ' . $quiz['title'] . ' - MUSLIM UPSKILL ACADEMY',
            'quiz'             => $quiz,
            'attempts'         => $attempts,
            'filter'           => $filter,
            'search'           => $search,
            'total_attempts'   => $totalAttempts,
            'registered_count' => $registeredCount,
            'guest_count'      => $guestCount,
            'conversion_rate'  => $conversionRate,
            'avg_score'        => $avgScore,
            'admin_name'       => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email'      => session()->get('email') ?? 'admin@matla.id',
        ]);
    }

    /**
     * Menghapus 1 baris riwayat pengerjaan (berguna untuk membersihkan data testing).
     */
    public function deleteAttempt(int $id)
    {
        $attempt = $this->attemptModel->find($id);
        if (! $attempt) {
            return redirect()->back()->with('error', 'Data riwayat pengerjaan tidak ditemukan.');
        }

        $quizId = (int) $attempt['quiz_id'];
        $this->attemptModel->delete($id);

        return redirect()->to(base_url('admin/trial-quizzes/' . $quizId . '/attempts'))
            ->with('success', 'Riwayat pengerjaan berhasil dihapus.');
    }

    private function resolveHsiPredicate(int $scoreInt): array
    {
        if ($scoreInt >= 100) {
            return ['label' => "Mumtaz Murtafi'", 'tone' => 'emerald'];
        }
        if ($scoreInt >= 90) {
            return ['label' => 'Mumtaz', 'tone' => 'emerald'];
        }
        if ($scoreInt >= 80) {
            return ['label' => 'Jayyid Jiddan', 'tone' => 'blue'];
        }
        if ($scoreInt >= 70) {
            return ['label' => 'Jayyid', 'tone' => 'indigo'];
        }
        if ($scoreInt >= 50) {
            return ['label' => 'Maqbul', 'tone' => 'amber'];
        }
        if ($scoreInt >= 1) {
            return ['label' => 'Rasib', 'tone' => 'rose'];
        }

        return ['label' => 'Ghayyib', 'tone' => 'rose'];
    }
}

