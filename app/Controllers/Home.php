<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\TrialQuizModel;
use App\Models\TrialQuizQuestionModel;
use Config\Database;

class Home extends BaseController
{
    /**
     * Halaman Beranda Utama platform MUSLIM UPSKILL ACADEMY
     *
     * @return string
     */
    public function index(): string
    {
        $courseModel = new CourseModel();

        // Mengambil daftar kelas yang berstatus 'published' (6 kelas terbaru)
        $courses = $courseModel->where('status', 'published')
                               ->orderBy('id', 'DESC')
                               ->findAll(6);

        // Mengambil pratinjau Quiz Trial Utama secara aman (tidak error jika tabel belum dimigrasi)
        $featuredTrial = null;
        $sampleQuestion = null;
        $publishedQuizCount = 0;

        $db = Database::connect();
        if ($db->tableExists('trial_quizzes') && $db->tableExists('trial_quiz_questions')) {
            $trialModel    = new TrialQuizModel();
            $questionModel = new TrialQuizQuestionModel();

            $featuredTrial = $trialModel->getFeaturedTrial();
            if ($featuredTrial) {
                $publicQuestions = $questionModel->getPublicByQuiz((int) $featuredTrial['id']);
                $sampleQuestion  = $publicQuestions[0] ?? null;
            }

            $publishedQuizCount = $trialModel->where('status', 'published')->countAllResults();
        }

        $data = [
            'title'                => 'MUSLIM UPSKILL ACADEMY - Platform Peningkatan Kompetensi',
            'courses'              => $courses,
            'featured_trial'       => $featuredTrial,
            'sample_question'      => $sampleQuestion,
            'published_quiz_count' => $publishedQuizCount,
        ];

        return view('home/index', $data);
    }
}
