<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class Course extends BaseController
{
    protected CourseModel $courseModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
    }

    /**
     * Menampilkan daftar seluruh kelas pada panel admin
     *
     * @return string
     */
    public function index(): string
    {
        $courses = $this->courseModel->getCoursesWithMentor();

        $data = [
            'title'       => 'Manajemen Katalog Kelas - MUSLIM UPSKILL ACADEMY',
            'courses'     => $courses,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/courses/index', $data);
    }

    /**
     * Menampilkan formulir tambah kelas baru
     *
     * @return string
     */
    public function create(): string
    {
        $data = [
            'title'       => 'Tambah Kelas Baru - MUSLIM UPSKILL ACADEMY',
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/courses/create', $data);
    }

    /**
     * Menyimpan data kelas baru ke database (HTTP POST)
     */
    public function store()
    {
        $rules = [
            'title'        => 'required|min_length[5]|max_length[255]',
            'status'       => 'required|in_list[draft,published]',
            'description'  => 'permit_empty',
            'banner_image' => 'permit_empty|is_image[banner_image]|mime_in[banner_image,image/jpg,image/jpeg,image/png,image/webp]|max_size[banner_image,2048]',
        ];

        $messages = [
            'title' => [
                'required'   => 'Judul kelas wajib diisi.',
                'min_length' => 'Judul kelas minimal terdiri dari 5 karakter.',
            ],
            'status' => [
                'required' => 'Status publikasi wajib dipilih.',
                'in_list'  => 'Status harus berupa draft atau published.',
            ],
            'banner_image' => [
                'is_image' => 'Berkas yang dipilih harus berupa file gambar valid.',
                'mime_in'  => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
                'max_size' => 'Ukuran berkas gambar maksimal adalah 2MB.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Proses unggah gambar promosi (banner_image)
        $file = $this->request->getFile('banner_image');
        $bannerName = 'default.png';

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/courses', $newName);
            $bannerName = $newName;
        }

        // Ambil mentor_id dari sesi admin aktif (default ID 1 jika belum terekam)
        $mentorId = (int) (session()->get('user_id') ?? 1);

        $courseData = [
            'mentor_id'    => $mentorId,
            'title'        => trim($this->request->getPost('title')),
            'description'  => trim($this->request->getPost('description')),
            'price'        => 0.00, // MVP: Sistem kelas gratis, set nilai default 0.00
            'banner_image' => $bannerName,
            'status'       => (string) $this->request->getPost('status'),
        ];

        $this->courseModel->insert($courseData);

        return redirect()->to(base_url('admin/courses'))->with('success', 'Kelas baru berhasil ditambahkan ke katalog!');
    }

    /**
     * Menampilkan formulir edit kelas katalog
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function edit(int $id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Kelas tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Kelas: ' . esc($course['title']) . ' - MUSLIM UPSKILL ACADEMY',
            'course'      => $course,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/courses/edit', $data);
    }

    /**
     * Memperbarui data kelas di katalog (HTTP POST)
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Kelas tidak ditemukan.');
        }

        $rules = [
            'title'        => 'required|min_length[5]|max_length[255]',
            'status'       => 'required|in_list[draft,published]',
            'description'  => 'permit_empty',
            'banner_image' => 'permit_empty|is_image[banner_image]|mime_in[banner_image,image/jpg,image/jpeg,image/png,image/webp]|max_size[banner_image,2048]',
        ];

        $messages = [
            'title' => [
                'required'   => 'Judul kelas wajib diisi.',
                'min_length' => 'Judul kelas minimal terdiri dari 5 karakter.',
            ],
            'status' => [
                'required' => 'Status publikasi wajib dipilih.',
                'in_list'  => 'Status harus berupa draft atau published.',
            ],
            'banner_image' => [
                'is_image' => 'Berkas yang dipilih harus berupa file gambar valid.',
                'mime_in'  => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
                'max_size' => 'Ukuran berkas gambar maksimal adalah 2MB.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $courseData = [
            'title'       => trim((string) $this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'status'      => (string) $this->request->getPost('status'),
        ];

        // Proses unggah banner baru jika ada
        $file = $this->request->getFile('banner_image');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/courses', $newName);
            $courseData['banner_image'] = $newName;

            // Hapus banner lama jika bukan default dan file fisiknya ada
            if (!empty($course['banner_image']) && $course['banner_image'] !== 'default.png') {
                $oldPath = FCPATH . 'uploads/courses/' . $course['banner_image'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $this->courseModel->update($id, $courseData);

        return redirect()->to(base_url('admin/courses'))->with('success', 'Data kelas "' . esc($courseData['title']) . '" berhasil diperbarui!');
    }

    /**
     * Menghapus kelas dari database
     */
    public function delete(int $id)
    {
        $course = $this->courseModel->find($id);
        if ($course) {
            $this->courseModel->delete($id);
            return redirect()->to(base_url('admin/courses'))->with('success', 'Kelas "' . esc($course['title']) . '" berhasil dihapus.');
        }

        return redirect()->to(base_url('admin/courses'))->with('error', 'Kelas tidak ditemukan.');
    }
}
