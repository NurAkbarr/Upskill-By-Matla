<?php

namespace App\Controllers;

use App\Models\TrialQuizAttemptModel;
use App\Models\TrialQuizModel;
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

        $this->capturePendingTrialFromQuery();

        $data = [
            'title'         => 'Masuk - UPSKILL by MATLA',
            'trial_context' => $this->getPendingTrialContext(),
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

        // Klaim hasil Trial Quiz jika sebelumnya pengguna mengerjakan quiz sebagai tamu
        $pendingToken = (string) (session()->get('pending_trial_token') ?? '');
        if ($pendingToken !== '') {
            $attemptModel = new TrialQuizAttemptModel();
            $claimed      = $attemptModel->claimByToken($pendingToken, (int) $user['id']);
            session()->remove('pending_trial_token');

            if ($claimed) {
                session()->remove('redirect_after_auth');
                return redirect()->to(base_url('quiz/result/' . $pendingToken))
                    ->with('success', 'Berhasil masuk! Skor trial Anda tersimpan dan seluruh pembahasan kini terbuka.');
            }
        }

        // Arahkan kembali ke quiz tujuan jika sebelumnya diblokir login
        $redirectAfter = $this->consumeSafeQuizRedirect();
        if ($redirectAfter !== null) {
            return redirect()->to(base_url($redirectAfter));
        }

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

        $this->capturePendingTrialFromQuery();

        $data = [
            'title'         => 'Pendaftaran Akun - UPSKILL by MATLA',
            'trial_context' => $this->getPendingTrialContext(),
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

        $password = (string) $this->request->getPost('password');

        $userData = [
            'full_name'     => trim($this->request->getPost('full_name')),
            'email'         => strtolower(trim($this->request->getPost('email'))),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'peserta_b2c',
            'instansi_name' => null,
        ];

        $this->userModel->insert($userData);
        $newUserId = (int) $this->userModel->getInsertID();

        $pendingToken  = (string) (session()->get('pending_trial_token') ?? '');
        $redirectAfter = $this->consumeSafeQuizRedirect();

        // Jika pendaftaran berasal dari funnel Trial Quiz / Quiz Lanjutan, langsung auto-login & klaim skor
        if ($pendingToken !== '' || $redirectAfter !== null) {
            session()->set([
                'user_id'       => $newUserId,
                'full_name'     => $userData['full_name'],
                'email'         => $userData['email'],
                'role'          => $userData['role'],
                'instansi_name' => null,
                'isLoggedIn'    => true,
            ]);

            if ($pendingToken !== '') {
                $attemptModel = new TrialQuizAttemptModel();
                $claimed      = $attemptModel->claimByToken($pendingToken, $newUserId);
                session()->remove('pending_trial_token');

                if ($claimed) {
                    return redirect()->to(base_url('quiz/result/' . $pendingToken))
                        ->with('success', 'Akun berhasil dibuat! Skor trial Anda telah tersimpan dan seluruh pembahasan kini terbuka.');
                }
            }

            if ($redirectAfter !== null) {
                return redirect()->to(base_url($redirectAfter))
                    ->with('success', 'Akun berhasil dibuat! Selamat mengerjakan quiz.');
            }
        }

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
     * Menyimpan token trial dari query string (jika valid dan belum diklaim).
     */
    private function capturePendingTrialFromQuery(): void
    {
        $queryToken = trim((string) ($this->request->getGet('trial_token') ?? ''));
        if ($queryToken === '') {
            return;
        }

        $attemptModel = new TrialQuizAttemptModel();
        $attempt      = $attemptModel->findByToken($queryToken);

        if ($attempt && $attempt['user_id'] === null) {
            session()->set('pending_trial_token', $queryToken);
        }
    }

    /**
     * Mengambil ringkasan konteks Trial Quiz yang sedang tertunda untuk ditampilkan di halaman login/register.
     */
    private function getPendingTrialContext(): ?array
    {
        $token = (string) (session()->get('pending_trial_token') ?? '');
        if ($token === '') {
            return null;
        }

        $attemptModel = new TrialQuizAttemptModel();
        $attempt      = $attemptModel->findByToken($token);

        if (! $attempt || $attempt['user_id'] !== null) {
            return null;
        }

        $quizModel = new TrialQuizModel();
        $quiz      = $quizModel->find((int) $attempt['quiz_id']);

        if (! $quiz) {
            return null;
        }

        return [
            'quiz_title'      => $quiz['title'],
            'score'           => (int) round((float) $attempt['score']),
            'correct_count'   => (int) $attempt['correct_count'],
            'total_questions' => (int) $attempt['total_questions'],
        ];
    }

    /**
     * Mengambil tujuan redirect internal yang aman (hanya rute quiz/*).
     */
    private function consumeSafeQuizRedirect(): ?string
    {
        $target = trim((string) (session()->get('redirect_after_auth') ?? ''));
        session()->remove('redirect_after_auth');

        if ($target !== '' && preg_match('#^quiz/[a-zA-Z0-9_-]+$#', $target)) {
            return $target;
        }

        return null;
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
