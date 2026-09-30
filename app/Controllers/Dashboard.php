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
}
