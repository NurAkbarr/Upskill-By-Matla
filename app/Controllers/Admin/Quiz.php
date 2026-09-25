<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\QuizQuestionModel;

class Quiz extends BaseController
{
    protected CourseModel $courseModel;
    protected LessonModel $lessonModel;
    protected QuizQuestionModel $quizModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->lessonModel = new LessonModel();
        $this->quizModel   = new QuizQuestionModel();
    }

    /**
     * Menampilkan halaman khusus kelola soal kuis pada sesi tertentu
     *
     * @param int $sessionId
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index(int $sessionId)
    {
        $session = $this->lessonModel->find($sessionId);
        if (!$session) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $course    = $this->courseModel->find($session['course_id']);
        $questions = $this->quizModel->getQuestionsBySession($sessionId);

        $data = [
            'title'       => 'Kelola Kuis: ' . esc($session['chapter_title']) . ' - MUSLIM UPSKILL ACADEMY',
            'session'     => $session,
            'course'      => $course,
            'questions'   => $questions,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/quiz/index', $data);
    }

    /**
     * Menyimpan butir soal kuis pilihan ganda baru (HTTP POST)
     *
     * @param int $sessionId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(int $sessionId)
    {
        $session = $this->lessonModel->find($sessionId);
        if (!$session) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $rules = [
            'question_text'  => 'required|min_length[5]',
            'option_a'       => 'required',
            'option_b'       => 'required',
            'option_c'       => 'required',
            'option_d'       => 'required',
            'correct_answer' => 'required|in_list[a,b,c,d]',
        ];

        $messages = [
            'question_text' => [
                'required'   => 'Teks pertanyaan/soal kuis wajib diisi.',
                'min_length' => 'Teks pertanyaan minimal terdiri dari 5 karakter.',
            ],
            'option_a' => [
                'required' => 'Pilihan jawaban A wajib diisi.',
            ],
            'option_b' => [
                'required' => 'Pilihan jawaban B wajib diisi.',
            ],
            'option_c' => [
                'required' => 'Pilihan jawaban C wajib diisi.',
            ],
            'option_d' => [
                'required' => 'Pilihan jawaban D wajib diisi.',
            ],
            'correct_answer' => [
                'required' => 'Kunci jawaban yang benar wajib ditentukan.',
                'in_list'  => 'Kunci jawaban harus berupa opsi A, B, C, atau D.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'session_id'     => $sessionId,
            'question_text'  => trim((string) $this->request->getPost('question_text')),
            'option_a'       => trim((string) $this->request->getPost('option_a')),
            'option_b'       => trim((string) $this->request->getPost('option_b')),
            'option_c'       => trim((string) $this->request->getPost('option_c')),
            'option_d'       => trim((string) $this->request->getPost('option_d')),
            'correct_answer' => (string) $this->request->getPost('correct_answer'),
        ];

        $this->quizModel->insert($data);

        return redirect()->to(base_url('admin/sessions/' . $sessionId . '/quiz'))
                         ->with('success', 'Butir soal kuis baru berhasil ditambahkan!');
    }

    /**
     * Menghapus butir soal kuis berdasarkan ID
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id)
    {
        $question = $this->quizModel->find($id);
        if (!$question) {
            return redirect()->back()->with('error', 'Butir soal tidak ditemukan.');
        }

        $sessionId = (int) $question['session_id'];
        $this->quizModel->delete($id);

        return redirect()->to(base_url('admin/sessions/' . $sessionId . '/quiz'))
                         ->with('success', 'Butir soal kuis berhasil dihapus.');
    }
}
