<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan daftar seluruh pengguna terdaftar dengan paginasi
     *
     * @return string
     */
    public function index(): string
    {
        $perPage = 10;
        $users   = $this->userModel->orderBy('created_at', 'DESC')->paginate($perPage);

        $data = [
            'title'          => 'Kelola Pengguna - MUSLIM UPSKILL ACADEMY',
            'users'          => $users,
            'pager'          => $this->userModel->pager,
            'currentPage'    => $this->userModel->pager->getCurrentPage() ?? 1,
            'perPage'        => $perPage,
            'currentAdminId' => (int) (session()->get('user_id') ?? 0),
            'admin_name'     => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email'    => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Menampilkan formulir pendaftaran pengguna baru oleh Admin
     *
     * @return string
     */
    public function create(): string
    {
        $data = [
            'title'       => 'Tambah Pengguna Baru - MUSLIM UPSKILL ACADEMY',
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/users/create', $data);
    }

    /**
     * Menyimpan data pengguna baru ke database (HTTP POST)
     */
    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'role'      => 'required|in_list[super_admin,mentor,peserta_b2c,peserta_b2b]',
        ];

        $messages = [
            'full_name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Alamat email sudah digunakan oleh akun lain.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal terdiri dari 6 karakter.',
            ],
            'role' => [
                'required' => 'Peran (Role) pengguna wajib dipilih.',
                'in_list'  => 'Role yang dipilih tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'full_name'     => trim($this->request->getPost('full_name')),
            'email'         => strtolower(trim($this->request->getPost('email'))),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => (string) $this->request->getPost('role'),
            'instansi_name' => trim((string) $this->request->getPost('instansi_name')) ?: null,
        ];

        $this->userModel->insert($userData);

        return redirect()->to(base_url('admin/users'))->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir edit data pengguna
     *
     * @param int $id
     */
    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Pengguna - MUSLIM UPSKILL ACADEMY',
            'user'        => $user,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Memperbarui data pengguna yang ada (HTTP POST)
     *
     * @param int $id
     */
    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'      => 'required|in_list[super_admin,mentor,peserta_b2c,peserta_b2b]',
            'password'  => 'permit_empty|min_length[6]',
        ];

        $messages = [
            'full_name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Alamat email sudah digunakan oleh akun lain.',
            ],
            'role' => [
                'required' => 'Peran (Role) pengguna wajib dipilih.',
                'in_list'  => 'Role yang dipilih tidak valid.',
            ],
            'password' => [
                'min_length' => 'Password baru minimal harus 6 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'full_name'     => trim($this->request->getPost('full_name')),
            'email'         => strtolower(trim($this->request->getPost('email'))),
            'role'          => (string) $this->request->getPost('role'),
            'instansi_name' => trim((string) $this->request->getPost('instansi_name')) ?: null,
        ];

        // Jika password diisi, lakukan hash dan perbarui password_hash
        $password = (string) $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $userData);

        return redirect()->to(base_url('admin/users'))->with('success', 'Data pengguna "' . esc($userData['full_name']) . '" berhasil diperbarui!');
    }

    /**
     * Menghapus pengguna berdasarkan ID dengan proteksi akun aktif
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id)
    {
        $currentUserId = (int) (session()->get('user_id') ?? 0);

        // Proteksi: Admin tidak boleh menghapus akunnya sendiri
        if ($id === $currentUserId) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Tindakan dibatalkan: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $this->userModel->delete($id);

        return redirect()->to(base_url('admin/users'))->with('success', 'Pengguna "' . esc($user['full_name']) . '" berhasil dihapus dari sistem.');
    }
}
