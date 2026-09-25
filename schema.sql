-- ==========================================================
-- Database Schema: UPSKILL by MATLA
-- Deployment Target: Shared Hosting (MySQL 5.7+ / MariaDB 10.3+)
-- Engine: InnoDB (Support Foreign Key Constraints)
-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `enrollments`;
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
-- Materi terstruktur di dalam kursus (video YouTube atau teks/PDF) + Tautan Kuis
-- ----------------------------------------------------------
CREATE TABLE `lessons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NOT NULL,
    `chapter_title` VARCHAR(150) NOT NULL COMMENT 'Nama Sesi',
    `content_type` ENUM('video', 'text_pdf', 'text') NOT NULL DEFAULT 'video',
    `content_url_or_text` TEXT DEFAULT NULL COMMENT 'URL Video YouTube atau Isi Konten Dokumen Teks/PDF',
    `quiz_url` VARCHAR(255) DEFAULT NULL COMMENT 'Tautan formulir kuis / evaluasi akhir sesi (Google Form, dll)',
    `order_index` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_lessons_course` (`course_id`),
    INDEX `idx_lessons_order` (`course_id`, `order_index`),
    CONSTRAINT `fk_lessons_course` 
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. Tabel enrollments
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
-- 5. Tabel transactions
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
