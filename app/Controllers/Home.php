<?php

namespace App\Controllers;

use App\Models\CourseModel;

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

        // Mengambil daftar kelas yang berstatus 'published' (misal: 6 kelas terbaru)
        $courses = $courseModel->where('status', 'published')
                               ->orderBy('id', 'DESC')
                               ->findAll(6);

        $data = [
            'title'   => 'MUSLIM UPSKILL ACADEMY - Platform Peningkatan Kompetensi',
            'courses' => $courses,
        ];

        return view('home/index', $data);
    }
}
