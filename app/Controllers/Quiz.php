<?php

namespace App\Controllers;

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
     * Halaman ujian CBT interaktif untuk peserta
     *
     * @param int $sessionId
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function cbt(int $sessionId)
    {
        $session = $this->lessonModel->find($sessionId);
        if (!$session) {
            return redirect()->to(base_url('courses'))->with('error', 'Sesi kuis tidak ditemukan.');
        }

        $course = $this->courseModel->find((int) $session['course_id']);
        if (!$course) {
            return redirect()->to(base_url('courses'))->with('error', 'Kelas tidak ditemukan.');
        }

        $currentTime = date('Y-m-d H:i:s');

        // Cek Pembatasan Waktu Mulai (Jadwal Buka)
        if (!empty($session['quiz_start_time']) && $currentTime < $session['quiz_start_time']) {
            $data = [
                'title'   => 'Kuis Belum Dibuka - ' . esc($session['chapter_title']),
                'session' => $session,
                'course'  => $course,
                'status'  => 'not_started',
                'message' => 'Ujian CBT ini dijadwalkan dibuka pada: ' . date('d M Y, H:i', strtotime($session['quiz_start_time'])) . ' WIB.',
            ];
            return view('quiz/cbt_notice', $data);
        }

        // Cek Pembatasan Waktu Selesai (Jadwal Tutup)
        if (!empty($session['quiz_end_time']) && $currentTime > $session['quiz_end_time']) {
            $data = [
                'title'   => 'Kuis Telah Berakhir - ' . esc($session['chapter_title']),
                'session' => $session,
                'course'  => $course,
                'status'  => 'ended',
                'message' => 'Ujian CBT ini telah resmi ditutup pada: ' . date('d M Y, H:i', strtotime($session['quiz_end_time'])) . ' WIB.',
            ];
            return view('quiz/cbt_notice', $data);
        }

        $questions = $this->quizModel->getQuestionsBySession($sessionId);
        $duration  = !empty($session['quiz_duration_minutes']) ? (int) $session['quiz_duration_minutes'] : 30;

        $data = [
            'title'       => 'CBT Ujian: ' . esc($session['chapter_title']) . ' - ' . esc($course['title']),
            'session'     => $session,
            'course'      => $course,
            'questions'   => $questions,
            'duration'    => $duration,
            'studentName' => session()->get('full_name') ?? 'Peserta Upskill',
        ];

        return view('quiz/cbt', $data);
    }

    /**
     * Memproses penyerahan jawaban ujian CBT
     *
     * @param int $sessionId
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function submitCbt(int $sessionId)
    {
        $session = $this->lessonModel->find($sessionId);
        if (!$session) {
            return redirect()->to(base_url('courses'))->with('error', 'Sesi kuis tidak ditemukan.');
        }

        $course    = $this->courseModel->find((int) $session['course_id']);
        $questions = $this->quizModel->getQuestionsBySession($sessionId);
        $answers   = $this->request->getPost('answers') ?? [];

        // Hitung skor untuk soal Pilihan Ganda (PG)
        $totalPg   = 0;
        $correctPg = 0;
        $totalEssay = 0;

        foreach ($questions as $q) {
            if (($q['question_type'] ?? 'pg') === 'essay') {
                $totalEssay++;
            } else {
                $totalPg++;
                $submittedAnswer = $answers[$q['id']] ?? null;
                if ($submittedAnswer && strtolower((string) $submittedAnswer) === strtolower((string) $q['correct_answer'])) {
                    $correctPg++;
                }
            }
        }

        $score = $totalPg > 0 ? round(($correctPg / $totalPg) * 100) : 100;

        $data = [
            'title'      => 'Hasil Ujian CBT - ' . esc($session['chapter_title']),
            'session'    => $session,
            'course'     => $course,
            'totalPg'    => $totalPg,
            'correctPg'  => $correctPg,
            'totalEssay' => $totalEssay,
            'score'      => $score,
        ];

        return view('quiz/cbt_result', $data);
    }
}
