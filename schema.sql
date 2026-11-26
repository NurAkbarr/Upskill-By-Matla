-- ==========================================================
-- Database Schema: UPSKILL by MATLA
-- Deployment Target: Shared Hosting (MySQL 5.7+ / MariaDB 10.3+)
-- Engine: InnoDB (Support Foreign Key Constraints)
-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `trial_quiz_attempts`;
DROP TABLE IF EXISTS `trial_quiz_questions`;
DROP TABLE IF EXISTS `trial_quizzes`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `session_progress`;
DROP TABLE IF EXISTS `enrollments`;
DROP TABLE IF EXISTS `quiz_questions`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------
-- 1. Tabel users
-- Menyimpan kredensial dan profil pengguna platform
-- ----------------------------------------------------------
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('super_admin', 'mentor', 'peserta_b2c', 'peserta_b2b') NOT NULL,
    `instansi_name` VARCHAR(100) DEFAULT NULL COMMENT 'Nama instansi khusus peserta_b2b atau mentor instansi',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_users_email` (`email`),
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. Tabel courses
-- Menyimpan katalog kelas/kursus
-- ----------------------------------------------------------
CREATE TABLE `courses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `mentor_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT '0.00 menandakan kursus gratis',
    `banner_image` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_courses_slug` (`slug`),
    INDEX `idx_courses_mentor` (`mentor_id`),
    INDEX `idx_courses_status` (`status`),
    CONSTRAINT `fk_courses_mentor` 
        FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. Tabel lessons (Sesi Pembelajaran & Evaluasi Kuis)
-- Materi terstruktur di dalam kursus (video YouTube atau teks/PDF) + CBT Kuis
-- ----------------------------------------------------------
CREATE TABLE `lessons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NOT NULL,
    `chapter_title` VARCHAR(150) NOT NULL COMMENT 'Nama Sesi',
    `content_type` ENUM('video', 'text_pdf', 'text') NOT NULL DEFAULT 'video',
    `content_url_or_text` TEXT DEFAULT NULL COMMENT 'URL Video YouTube atau Isi Konten Dokumen Teks/PDF',
    `quiz_url` VARCHAR(255) DEFAULT NULL COMMENT 'Tautan formulir kuis / evaluasi akhir sesi',
    `order_index` INT NOT NULL DEFAULT 1,
    `quiz_start_time` DATETIME DEFAULT NULL,
    `quiz_end_time` DATETIME DEFAULT NULL,
    `quiz_duration_minutes` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_lessons_course` (`course_id`),
    INDEX `idx_lessons_order` (`course_id`, `order_index`),
    CONSTRAINT `fk_lessons_course` 
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. Tabel quiz_questions (Soal Kuis Sesi)
-- Menyimpan butir soal kuis evaluasi per sesi
-- ----------------------------------------------------------
CREATE TABLE `quiz_questions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `session_id` INT UNSIGNED NOT NULL,
    `question_text` TEXT NOT NULL,
    `question_type` ENUM('pg', 'essay') NOT NULL DEFAULT 'pg',
    `option_a` TEXT DEFAULT NULL,
    `option_b` TEXT DEFAULT NULL,
    `option_c` TEXT DEFAULT NULL,
    `option_d` TEXT DEFAULT NULL,
    `correct_answer` ENUM('a', 'b', 'c', 'd') DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_quiz_session` (`session_id`),
    CONSTRAINT `fk_quiz_session` 
        FOREIGN KEY (`session_id`) REFERENCES `lessons` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 5. Tabel enrollments
-- Data kepesertaan kelas serta progress belajar
-- ----------------------------------------------------------
CREATE TABLE `enrollments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `progress_percentage` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'completed') NOT NULL DEFAULT 'active',
    `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_user_course` (`user_id`, `course_id`),
    INDEX `idx_enrollments_user` (`user_id`),
    INDEX `idx_enrollments_course` (`course_id`),
    INDEX `idx_enrollments_status` (`status`),
    CONSTRAINT `fk_enrollments_user` 
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT `fk_enrollments_course` 
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 6. Tabel session_progress
-- Pelacakan progres belajar materi sesi dan evaluasi kuis peserta
-- ----------------------------------------------------------
CREATE TABLE `session_progress` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `session_id` INT UNSIGNED NOT NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `quiz_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `quiz_score` DECIMAL(5, 2) DEFAULT NULL,
    `quiz_completed_at` DATETIME DEFAULT NULL,
    `completed_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `user_session_idx` (`user_id`, `session_id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_session` (`session_id`),
    CONSTRAINT `fk_progress_user` 
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT `fk_progress_session` 
        FOREIGN KEY (`session_id`) REFERENCES `lessons` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 7. Tabel transactions
-- Pencatatan riwayat transaksi dan pembayaran
-- ----------------------------------------------------------
CREATE TABLE `transactions` (
    `id` VARCHAR(50) NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED DEFAULT NULL,
    `amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
    `payment_method` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_transactions_user` (`user_id`),
    INDEX `idx_transactions_course` (`course_id`),
    INDEX `idx_transactions_status` (`status`),
    CONSTRAINT `fk_transactions_user` 
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    CONSTRAINT `fk_transactions_course` 
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 8. Tabel trial_quizzes
-- Quiz mandiri terpisah dari katalog kelas (1 Trial Utama tanpa login + quiz lanjutan khusus member)
-- ----------------------------------------------------------
CREATE TABLE `trial_quizzes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL,
    `topic_label` VARCHAR(80) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `is_trial` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = dapat dikerjakan tanpa login & menjadi tujuan menu Quiz',
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    `duration_minutes` INT UNSIGNED NOT NULL DEFAULT 0,
    `recommended_course_id` INT UNSIGNED DEFAULT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_trial_quizzes_slug` (`slug`),
    INDEX `idx_trial_quizzes_status_trial` (`status`, `is_trial`),
    CONSTRAINT `fk_trial_quizzes_course`
        FOREIGN KEY (`recommended_course_id`) REFERENCES `courses` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    CONSTRAINT `fk_trial_quizzes_creator`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 9. Tabel trial_quiz_questions
-- Butir soal pilihan ganda beserta pembahasan & dalil
-- ----------------------------------------------------------
CREATE TABLE `trial_quiz_questions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `quiz_id` INT UNSIGNED NOT NULL,
    `question_text` TEXT NOT NULL,
    `option_a` TEXT NOT NULL,
    `option_b` TEXT NOT NULL,
    `option_c` TEXT NOT NULL,
    `option_d` TEXT NOT NULL,
    `correct_answer` ENUM('a', 'b', 'c', 'd') NOT NULL,
    `explanation` TEXT DEFAULT NULL,
    `order_index` INT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_trial_questions_quiz_order` (`quiz_id`, `order_index`),
    CONSTRAINT `fk_trial_questions_quiz`
        FOREIGN KEY (`quiz_id`) REFERENCES `trial_quizzes` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 10. Tabel trial_quiz_attempts
-- Riwayat pengerjaan quiz trial (guest) & member beserta auto-claim
-- ----------------------------------------------------------
CREATE TABLE `trial_quiz_attempts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `quiz_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `attempt_token` VARCHAR(64) NOT NULL,
    `score` DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    `correct_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `total_questions` INT UNSIGNED NOT NULL DEFAULT 0,
    `duration_seconds` INT UNSIGNED NOT NULL DEFAULT 0,
    `answers_json` LONGTEXT DEFAULT NULL,
    `claimed_at` DATETIME DEFAULT NULL,
    `completed_at` DATETIME NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_trial_attempts_token` (`attempt_token`),
    INDEX `idx_trial_attempts_quiz` (`quiz_id`),
    INDEX `idx_trial_attempts_user` (`user_id`),
    CONSTRAINT `fk_trial_attempts_quiz`
        FOREIGN KEY (`quiz_id`) REFERENCES `trial_quizzes` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_trial_attempts_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
