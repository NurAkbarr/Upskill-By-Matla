<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;

class Lesson extends BaseController
{
    protected CourseModel $courseModel;
    protected LessonModel $lessonModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->lessonModel = new LessonModel();
    }

    /**
     * Menampilkan daftar sesi kurikulum dan formulir pembuatan sesi baru
     *
     * @param int $courseId
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index(int $courseId)
    {
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Kelas tidak ditemukan.');
        }

        $lessons   = $this->lessonModel->getLessonsByCourse($courseId);
        $nextOrder = $this->lessonModel->getNextOrderIndex($courseId);

        // Hitung total butir kuis manual yang sudah dibuat untuk tiap sesi
        $quizModel = new \App\Models\QuizQuestionModel();
        foreach ($lessons as &$l) {
            $l['quiz_count'] = $quizModel->countBySession((int) $l['id']);
        }
        unset($l);

        $data = [
            'title'       => 'Manajemen Kurikulum Sesi: ' . esc($course['title']) . ' - MUSLIM UPSKILL ACADEMY',
            'course'      => $course,
            'lessons'     => $lessons,
            'nextOrder'   => $nextOrder,
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/lessons/index', $data);
    }

    /**
     * Menyimpan sesi pembelajaran baru (Materi Pelajaran)
     *
     * @param int $courseId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(int $courseId)
    {
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Kelas tidak ditemukan.');
        }

        $rules = [
            'chapter_title'       => 'required|min_length[3]|max_length[150]',
            'content_type'        => 'required|in_list[video,text_pdf]',
            'content_url_or_text' => 'permit_empty',
            'order_index'         => 'required|is_natural_no_zero',
        ];

        $messages = [
            'chapter_title' => [
                'required'   => 'Nama sesi wajib diisi (Contoh: Sesi 1 - Rukun Sholat).',
                'min_length' => 'Nama sesi minimal 3 karakter.',
            ],
            'content_type' => [
                'required' => 'Tipe konten utama sesi wajib dipilih.',
                'in_list'  => 'Tipe konten harus berupa Video YouTube atau Teks/PDF.',
            ],
            'order_index' => [
                'required'            => 'Nomor urutan sesi wajib diisi.',
                'is_natural_no_zero'  => 'Urutan sesi harus berupa angka positif (mulai dari 1).',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'course_id'           => $courseId,
            'chapter_title'       => trim((string) $this->request->getPost('chapter_title')),
            'content_type'        => (string) $this->request->getPost('content_type'),
            'content_url_or_text' => trim((string) $this->request->getPost('content_url_or_text')),
            'order_index'         => (int) ($this->request->getPost('order_index') ?: 1),
        ];

        $this->lessonModel->insert($data);

        return redirect()->to(base_url('admin/courses/' . $courseId . '/lessons'))
                         ->with('success', 'Sesi pembelajaran "' . esc($data['chapter_title']) . '" berhasil ditambahkan ke kurikulum!');
    }

    /**
     * Menampilkan formulir edit sesi pembelajaran
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function edit(int $id)
    {
        $lesson = $this->lessonModel->find($id);
        if (!$lesson) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $courseId = (int) $lesson['course_id'];
        $course   = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Kelas tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Sesi: ' . esc($lesson['chapter_title']) . ' - ' . esc($course['title']),
            'course'      => $course,
            'lesson'      => $lesson,
            'session'     => $lesson, // Alias untuk kompatibilitas view
            'admin_name'  => session()->get('full_name') ?? 'Super Admin MATLA',
            'admin_email' => session()->get('email') ?? 'admin@matla.id',
        ];

        return view('admin/lessons/edit', $data);
    }

    /**
     * Memperbarui sesi pembelajaran di kurikulum
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id)
    {
        $lesson = $this->lessonModel->find($id);
        if (!$lesson) {
            return redirect()->to(base_url('admin/courses'))->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $courseId = (int) $lesson['course_id'];

        $rules = [
            'chapter_title'       => 'required|min_length[3]|max_length[150]',
            'content_type'        => 'required|in_list[video,text_pdf]',
            'content_url_or_text' => 'permit_empty',
            'order_index'         => 'required|is_natural_no_zero',
        ];

        $messages = [
            'chapter_title' => [
                'required'   => 'Nama sesi wajib diisi (Contoh: Sesi 1 - Rukun Sholat).',
                'min_length' => 'Nama sesi minimal 3 karakter.',
            ],
            'content_type' => [
                'required' => 'Tipe konten utama sesi wajib dipilih.',
                'in_list'  => 'Tipe konten harus berupa Video YouTube atau Teks/PDF.',
            ],
            'order_index' => [
                'required'            => 'Nomor urutan sesi wajib diisi.',
                'is_natural_no_zero'  => 'Urutan sesi harus berupa angka positif (mulai dari 1).',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'chapter_title'       => trim((string) $this->request->getPost('chapter_title')),
            'content_type'        => (string) $this->request->getPost('content_type'),
            'content_url_or_text' => trim((string) $this->request->getPost('content_url_or_text')),
            'order_index'         => (int) ($this->request->getPost('order_index') ?: 1),
        ];

        $this->lessonModel->update($id, $updateData);

        return redirect()->to(base_url('admin/courses/' . $courseId . '/lessons'))
                         ->with('success', 'Sesi pembelajaran "' . esc($updateData['chapter_title']) . '" berhasil diperbarui!');
    }

    /**
     * Menghapus sesi pembelajaran
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id)
    {
        $lesson = $this->lessonModel->find($id);
        if (!$lesson) {
            return redirect()->back()->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $courseId = (int) $lesson['course_id'];
        $this->lessonModel->delete($id);

        return redirect()->to(base_url('admin/courses/' . $courseId . '/lessons'))
                         ->with('success', 'Sesi "' . esc($lesson['chapter_title']) . '" berhasil dihapus dari kurikulum.');
    }
}
