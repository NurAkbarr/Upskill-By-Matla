<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Course extends BaseController
{
    /**
     * Mengarahkan pengunjung langsung ke katalog program kelas aktif di beranda
     */
    public function index()
    {
        return redirect()->to(base_url('/#program-kelas'));
    }

    /**
     * Menampilkan detail kelas berdasarkan slug
     */
    public function detail(string $slug)
    {
        // Alihkan ke katalog program di beranda
        return redirect()->to(base_url('/#program-kelas'));
    }

    /**
     * Pendaftaran kelas langsung (MVP Gratis tanpa pembayaran)
     *
     * @param int $courseId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function enroll(int $courseId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan masuk ke akun Anda terlebih dahulu untuk mendaftar kelas.');
        }

        $courseModel = new CourseModel();
        $course = $courseModel->find($courseId);
        if (!$course) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Kelas tidak ditemukan.');
        }

        $enrollmentModel = new EnrollmentModel();
        $enrollmentModel->enrollUser($userId, $courseId);

        return redirect()->to(base_url('dashboard'))->with('success', 'Selamat! Anda berhasil terdaftar di program kelas "' . esc($course['title']) . '". Selamat belajar!');
    }
}
