<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'mentor_id',
        'title',
        'slug',
        'description',
        'price',
        'banner_image',
        'status',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false; // Kolom created_at ditangani oleh DEFAULT CURRENT_TIMESTAMP di MySQL

    // Callbacks untuk pembuatan slug otomatis
    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    /**
     * Otomatis membuat dan memperbarui slug dari kolom title
     *
     * @param array $data
     * @return array
     */
    protected function generateSlug(array $data): array
    {
        if (isset($data['data']['title']) && !empty($data['data']['title'])) {
            helper('url');
            $baseSlug = url_title($data['data']['title'], '-', true);
            $slug     = $baseSlug;

            // Periksa keunikan slug di tabel courses
            $builder = $this->builder();
            $currentId = $data['id'][0] ?? null;

            $builder->where('slug', $slug);
            if ($currentId) {
                $builder->where('id !=', $currentId);
            }

            if ($builder->countAllResults() > 0) {
                // Tambahkan akhiran acak unik jika slug sudah digunakan
                $slug = $baseSlug . '-' . substr(md5(uniqid((string)mt_rand(), true)), 0, 5);
            }

            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    /**
     * Mengambil seluruh data kursus beserta informasi nama mentor
     *
     * @return array
     */
    public function getCoursesWithMentor(): array
    {
        return $this->select('courses.*, users.full_name as mentor_name, users.email as mentor_email')
                    ->join('users', 'users.id = courses.mentor_id', 'left')
                    ->orderBy('courses.id', 'DESC')
                    ->findAll();
    }
}
