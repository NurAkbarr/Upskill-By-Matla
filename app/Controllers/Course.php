<?php

namespace App\Controllers;

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
}
