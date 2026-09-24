<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    /**
     * Halaman Utama Super Admin Panel
     * Terlindungi oleh filter AuthGuard & RoleGuard:super_admin
     *
     * @return string
     */
    public function index(): string
    {
        // Data statistik platform (dummy sementara untuk fase antarmuka)
        $statistics = [
            'total_peserta'      => 142,
            'total_pendapatan'   => 24500000, // Rp 24.500.000
            'total_kelas'        => 8,
            'tingkat_kelulusan'  => '87%',
        ];

        $data = [
            'title'             => 'Panel Super Admin - UPSKILL by MATLA',
            'admin_name'        => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email'       => session()->get('email') ?? 'admin@matla.id',
            'total_peserta'     => $statistics['total_peserta'],
            'total_pendapatan'  => $statistics['total_pendapatan'],
            'total_kelas'       => $statistics['total_kelas'],
            'tingkat_kelulusan' => $statistics['tingkat_kelulusan'],
        ];

        return view('admin/dashboard', $data);
    }
}
