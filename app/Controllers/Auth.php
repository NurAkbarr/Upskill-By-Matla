<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan halaman formulir login
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function login()
    {
        // Jika pengguna sudah memiliki sesi aktif, arahkan ke dasbor yang sesuai
        if (session()->get('isLoggedIn')) {
            return $this->redirectBasedOnRole(session()->get('role'));
        }

        $data = [
            'title' => 'Masuk - UPSKILL by MATLA',
        ];

        return view('auth/login', $data);
    }

    /**
     * Memproses data login pengguna (HTTP POST)
     */
    public function processLogin()
    {
        // Validasi kelengkapan data masukan
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format alamat email tidak valid.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = strtolower(trim($this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        // Cari pengguna di basis data
        $user = $this->userModel->findByEmail($email);

        // Verifikasi keberadaan user dan kecocokan hash password
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Kombinasi email dan password tidak sesuai.');
        }

        // Simpan data kredensial ke dalam Session CI4
        session()->set([
            'user_id'       => (int) $user['id'],
            'full_name'     => $user['full_name'],
            'email'         => $user['email'],
            'role'          => $user['role'],
            'instansi_name' => $user['instansi_name'],
            'isLoggedIn'    => true,
        ]);

        // Arahkan ke dasbor berdasarkan peran (role) pengguna
        return $this->redirectBasedOnRole($user['role']);
    }

    /**
     * Menampilkan formulir registrasi / pendaftaran akun
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectBasedOnRole(session()->get('role'));
        }

        $data = [
            'title' => 'Pendaftaran Akun - UPSKILL by MATLA',
        ];

        return view('auth/register', $data);
    }

    /**
     * Memproses data registrasi akun baru (HTTP POST)
     */
    public function processRegister()
    {
        $rules = [
            'full_name'        => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'full_name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Alamat email sudah terdaftar. Silakan gunakan email lain atau masuk.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal terdiri dari 6 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'full_name'     => trim($this->request->getPost('full_name')),
            'email'         => strtolower(trim($this->request->getPost('email'))),
            'password'      => (string) $this->request->getPost('password'),
            'role'          => 'peserta_b2c',
            'instansi_name' => null,
        ];

        $this->userModel->insert($userData);

        return redirect()->to(base_url('login'))->with('success', 'Akun berhasil dibuat! Silakan masuk dengan kredensial Anda.');
    }

    /**
     * Menghapus sesi login dan mengarahkan kembali ke halaman utama
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'))->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Pembantu pengalihan URL berdasarkan role pengguna
     */
    private function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'super_admin' => redirect()->to(base_url('admin/dashboard')),
            default       => redirect()->to(base_url('dashboard')),
        };
    }
}
