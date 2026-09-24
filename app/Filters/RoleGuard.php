<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleGuard implements FilterInterface
{
    /**
     * Memeriksa otorisasi role pengguna terhadap argumen role yang diizinkan
     * Contoh penggunaan rute: ['filter' => 'role:super_admin'] atau ['filter' => 'role:super_admin,mentor']
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Pastikan pengguna sudah terautentikasi
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userRole = session()->get('role');

        // 2. Jika ada argumen role khusus yang disyaratkan
        if (!empty($arguments)) {
            if (!in_array($userRole, $arguments, true)) {
                // Arahkan ke dasbor reguler dengan pesan penolakan
                return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses area Administrator.');
            }
        }
    }

    /**
     * Tidak ada tindakan pasca-eksekusi controller yang diperlukan
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosong
    }
}
