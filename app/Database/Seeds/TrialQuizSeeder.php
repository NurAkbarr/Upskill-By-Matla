<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TrialQuizSeeder extends Seeder
{
    public function run()
    {
        $quizTable     = $this->db->table('trial_quizzes');
        $questionTable = $this->db->table('trial_quiz_questions');

        // Hindari duplikasi jika sudah pernah dijalankan
        if ($quizTable->where('slug', 'tes-pemahaman-ilmu-tauhid-dasar')->countAllResults() > 0) {
            return;
        }

        // 1. Quiz Trial Utama (Tanpa Login) — Ilmu Tauhid (5 Soal)
        $quizTable->insert([
            'title'            => 'Tes Pemahaman Ilmu Tauhid Dasar',
            'slug'             => 'tes-pemahaman-ilmu-tauhid-dasar',
            'topic_label'      => 'Ilmu Tauhid',
            'description'      => 'Uji pemahaman dasar akidah dan pembagian tauhid melalui 5 pertanyaan ringkas beserta pembahasan dalilnya.',
            'is_trial'         => 1,
            'status'           => 'published',
            'duration_minutes' => 0,
            'created_by'       => 1,
        ]);

        $tauhidQuizId = (int) $this->db->insertID();

        $tauhidQuestions = [
            [
                'quiz_id'        => $tauhidQuizId,
                'order_index'    => 1,
                'question_text'  => 'Mengesakan Allah dalam seluruh bentuk ibadah seperti doa, sholat, nadzar, dan tawakal termasuk dalam cakupan tauhid jenis apa?',
                'option_a'       => 'Tauhid Rububiyyah',
                'option_b'       => 'Tauhid Uluhiyyah',
                'option_c'       => 'Tauhid Asma wa Shifat',
                'option_d'       => 'Tauhid Mulkiyyah',
                'correct_answer' => 'b',
                'explanation'    => 'Tauhid Uluhiyyah (atau Tauhid Ibadah) adalah mengesakan Allah dalam seluruh perbuatan hamba yang bernilai ibadah. Dalilnya antara lain firman Allah dalam QS. Al-Fatihah ayat 5: "Hanya kepada Engkaulah kami menyembah dan hanya kepada Engkaulah kami memohon pertolongan."',
            ],
            [
                'quiz_id'        => $tauhidQuizId,
                'order_index'    => 2,
                'question_text'  => 'Kaum musyrikin Quraisy pada masa Nabi Muhammad shallallahu alaihi wa sallam mengakui bahwa Allah adalah pencipta langit dan bumi. Mengapa pengakuan tersebut belum menjadikan mereka muslim?',
                'option_a'       => 'Karena mereka tidak mengenal bahasa Arab dengan baik',
                'option_b'       => 'Karena pengakuan itu baru mencakup Tauhid Rububiyyah, belum Tauhid Uluhiyyah',
                'option_c'       => 'Karena mereka menolak keberadaan malaikat sepenuhnya',
                'option_d'       => 'Karena mereka belum melaksanakan ibadah haji',
                'correct_answer' => 'b',
                'explanation'    => 'Al-Qur\'an menjelaskan bahwa kaum musyrikin mengakui Allah sebagai Pencipta dan Pemberi rezeki (QS. Yunus: 31), namun mereka masih memalingkan ibadah kepada selain Allah sebagai perantara. Inti dakwah para rasul adalah memurnikan Tauhid Uluhiyyah.',
            ],
            [
                'quiz_id'        => $tauhidQuizId,
                'order_index'    => 3,
                'question_text'  => 'Bagaimana sikap Ahlus Sunnah wal Jama\'ah terhadap nama-nama dan sifat-sifat Allah yang ditetapkan dalam Al-Qur\'an dan Hadis shahih?',
                'option_a'       => 'Menolaknya seluruhnya agar tidak menyerupai makhluk',
                'option_b'       => 'Menyamakan sifat Allah secara persis dengan sifat manusia',
                'option_c'       => 'Menetapkannya sesuai keagungan Allah tanpa tahrif, ta\'thil, takyif, dan tamtsil',
                'option_d'       => 'Mengubah maknanya sesuai selera akal semata',
                'correct_answer' => 'c',
                'explanation'    => 'Prinsip Tauhid Asma wa Shifat berlandaskan QS. Asy-Syura ayat 11: "Tidak ada sesuatu pun yang serupa dengan Dia, dan Dia-lah Yang Maha Mendengar lagi Maha Melihat." Kita menetapkan nama dan sifat Allah tanpa menyelewengkan makna, menolak, menanyakan bagaimananya, atau menyerupakan dengan makhluk.',
            ],
            [
                'quiz_id'        => $tauhidQuizId,
                'order_index'    => 4,
                'question_text'  => 'Manakah di antara pernyataan berikut yang merupakan dua rukun utama dari kalimat syahadat "Laa ilaaha illallah"?',
                'option_a'       => 'Niat (kehendak) dan ucapan lisan saja',
                'option_b'       => 'An-Nafyu (penafian sesembahan batil) dan Al-Itsbat (penetapan ibadah hanya bagi Allah)',
                'option_c'       => 'Rasa takut (khauf) dan rasa harap (raja\')',
                'option_d'       => 'Ilmu pengetahuan dan amal lahiriah semata',
                'correct_answer' => 'b',
                'explanation'    => 'Kalimat "Laa ilaaha" adalah An-Nafyu (menafikan seluruh sesembahan selain Allah), sedangkan "illallah" adalah Al-Itsbat (menetapkan ibadah semata-mata bagi Allah). Keduanya ditegaskan dalam QS. Al-Baqarah ayat 256 tentang kufur terhadap thaghut dan beriman kepada Allah.',
            ],
            [
                'quiz_id'        => $tauhidQuizId,
                'order_index'    => 5,
                'question_text'  => 'Di antara perbuatan berikut, manakah yang termasuk contoh syirik kecil (syirik ashghar) yang wajib dijauhi oleh seorang muslim?',
                'option_a'       => 'Berdoa meminta pertolongan kepada penghuni kubur',
                'option_b'       => 'Riya\' ringan dalam beribadah agar dipuji manusia',
                'option_c'       => 'Meyakini adanya pencipta selain Allah',
                'option_d'       => 'Menyembelih hewan kurban untuk selain Allah',
                'correct_answer' => 'b',
                'explanation'    => 'Nabi shallallahu alaihi wa sallam menyebut riya\' sebagai syirik kecil dalam hadis riwayat Ahmad: "Sesungguhnya yang paling aku khawatirkan atas kalian adalah syirik kecil." Ketika ditanya tentang itu, beliau menjawab: "Riya\'." Adapun berdoa dan menyembelih untuk selain Allah termasuk syirik besar.',
            ],
        ];

        $questionTable->insertBatch($tauhidQuestions);

        // 2. Quiz Lanjutan (Khusus Member — Butuh Login) — Fiqih Ibadah (3 Soal)
        $quizTable->insert([
            'title'            => 'Evaluasi Fiqih Sholat Sehari-hari',
            'slug'             => 'evaluasi-fiqih-sholat-sehari-hari',
            'topic_label'      => 'Fiqih Ibadah',
            'description'      => 'Uji ketepatan pemahaman syarat sah, rukun, dan perkara yang berkaitan dengan sholat wajib.',
            'is_trial'         => 0,
            'status'           => 'published',
            'duration_minutes' => 5,
            'created_by'       => 1,
        ]);

        $fiqihQuizId = (int) $this->db->insertID();

        $fiqihQuestions = [
            [
                'quiz_id'        => $fiqihQuizId,
                'order_index'    => 1,
                'question_text'  => 'Manakah di antara perkara berikut yang termasuk syarat sah sholat sebelum takbiratul ihram?',
                'option_a'       => 'Membaca doa iftitah',
                'option_b'       => 'Suci dari hadas kecil dan hadas besar',
                'option_c'       => 'Membaca surat pendek setelah Al-Fatihah',
                'option_d'       => 'Duduk iftirasy pada tasyahud awal',
                'correct_answer' => 'b',
                'explanation'    => 'Suci dari hadas kecil maupun besar adalah syarat sah sholat berdasarkan sabda Nabi shallallahu alaihi wa sallam (HR. Bukhari & Muslim): "Allah tidak menerima sholat salah seorang di antara kalian jika berhadas hingga ia berwudhu."',
            ],
            [
                'quiz_id'        => $fiqihQuizId,
                'order_index'    => 2,
                'question_text'  => 'Apa yang dimaksud dengan tuma\'ninah dalam rukun sholat?',
                'option_a'       => 'Membaca seluruh bacaan sholat dengan suara keras',
                'option_b'       => 'Berhenti sejenak hingga persendian tenang pada setiap gerakan rukun sholat',
                'option_c'       => 'Memanjangkan bacaan surat pada rakaat pertama saja',
                'option_d'       => 'Mengulang takbir dua kali sebelum rukuk',
                'correct_answer' => 'b',
                'explanation'    => 'Tuma\'ninah adalah ketenangan sejenak saat melakukan gerakan rukun (rukuk, i\'tidal, sujud, duduk di antara dua sujud) sebagaimana diajarkan Nabi kepada orang yang salah sholatnya (hadis al-musi\' sholatahu, HR. Bukhari & Muslim).',
            ],
            [
                'quiz_id'        => $fiqihQuizId,
                'order_index'    => 3,
                'question_text'  => 'Jika seorang makmum mendapati imam sedang dalam posisi rukuk lalu ia langsung takbiratul ihram dan ikut rukuk bersama imam dengan tuma\'ninah, bagaimana status rakaat tersebut?',
                'option_a'       => 'Tidak dihitung satu rakaat karena tertinggal Al-Fatihah',
                'option_b'       => 'Dihitung mendapatkan satu rakaat menurut jumhur ulama',
                'option_c'       => 'Wajib membatalkan sholat dan mengulang dari awal',
                'option_d'       => 'Harus sujud sahwi sebelum salam meskipun bersama imam',
                'correct_answer' => 'b',
                'explanation'    => 'Mayoritas (jumhur) ulama berpendapat makmum masbuq yang mendapati rukuk imam secara sempurna beserta tuma\'ninah dihitung mendapatkan rakaat tersebut, berdasarkan hadis Abu Bakrah radhiyallahu \'anhu dalam Shahih Al-Bukhari.',
            ],
        ];

        $questionTable->insertBatch($fiqihQuestions);
    }
}

