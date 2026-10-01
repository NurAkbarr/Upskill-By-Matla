<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\QuizQuestionModel;
use CodeIgniter\I18n\Time;

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

        // Ambil waktu sekarang secara akurat berdasarkan zona waktu Asia/Jakarta (Tahap 23)
        $now = Time::now('Asia/Jakarta');

        // Cek Pembatasan Waktu Mulai (Jadwal Buka)
        if (!empty($session['quiz_start_time'])) {
            $startTime = Time::parse($session['quiz_start_time'], 'Asia/Jakarta');
            if ($now->isBefore($startTime)) {
                $data = [
                    'title'   => 'Kuis Belum Dibuka - ' . esc($session['chapter_title']),
                    'session' => $session,
                    'course'  => $course,
                    'status'  => 'not_started',
                    'message' => 'Ujian CBT ini dijadwalkan dibuka pada: ' . date('d M Y, H:i', strtotime($session['quiz_start_time'])) . ' WIB.',
                ];
                return view('quiz/cbt_notice', $data);
            }
        }

        // Cek Pembatasan Waktu Selesai (Jadwal Tutup)
        if (!empty($session['quiz_end_time'])) {
            $endTime = Time::parse($session['quiz_end_time'], 'Asia/Jakarta');
            if ($now->isAfter($endTime)) {
                $data = [
                    'title'   => 'Kuis Telah Berakhir - ' . esc($session['chapter_title']),
                    'session' => $session,
                    'course'  => $course,
                    'status'  => 'ended',
                    'message' => 'Ujian CBT ini telah resmi ditutup pada: ' . date('d M Y, H:i', strtotime($session['quiz_end_time'])) . ' WIB.',
                ];
                return view('quiz/cbt_notice', $data);
            }
        }

        // Pastikan status materi sesi ini otomatis tercatat selesai 100% di database
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId > 0) {
            $progressModel = new \App\Models\SessionProgressModel();
            $progressModel->markCompleted($userId, $sessionId);

            // Update persentase kemajuan kelas di tabel enrollments
            $courseId    = (int) $session['course_id'];
            $allSessions = $this->lessonModel->getLessonsByCourse($courseId);
            $allIds      = array_column($allSessions, 'id');
            $progressMap = $progressModel->getProgressMap($userId, $allIds);
            $completed   = count(array_filter($progressMap));
            $pct         = count($allIds) > 0 ? round(($completed / count($allIds)) * 100) : 0;

            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enr = $enrollmentModel->where('user_id', $userId)->where('course_id', $courseId)->first();
            if ($enr) {
                $enrollmentModel->update($enr['id'], ['progress_percentage' => $pct]);
            }
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

        // Simpan hasil pengerjaan kuis peserta ke tabel session_progress
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId > 0) {
            $progressModel = new \App\Models\SessionProgressModel();
            $progressModel->markQuizCompleted($userId, $sessionId, $score);
        }

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
