<?php
/**
 * ==========================================================
 * UPSKILL by MATLA - Front Controller & Router
 * ==========================================================
 * File gerbang utama (entry point) aplikasi.
 * Menangani parsing query parameter URL dan meneruskan ke Controller terkait.
 * Contoh URL:
 *   - index.php (Default ke 'home')
 *   - index.php?page=home
 *   - index.php?page=login
 *   - index.php?page=courses
 */

// Mulai session global untuk autentikasi pengguna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Muat konfigurasi database & helper dasar
require_once __DIR__ . '/config/database.php';

// Definisi konstanta path root agar include file selalu presisi
define('BASE_PATH', __DIR__);

// Tangkap parameter 'page' dari URL (default ke 'home')
// Lakukan sanitasi dasar agar hanya menerima karakter alfanumerik dan garis bawah/strip
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['page']) : 'home';
$action = isset($_GET['action']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['action']) : 'index';

/**
 * Logika Routing Aplikasi
 * Memetakan parameter query 'page' ke Controller dan aksi yang sesuai
 */
switch ($page) {
    case 'home':
        // Routing untuk halaman utama / katalog unggulan
        $controllerFile = BASE_PATH . '/controllers/HomeController.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new HomeController();
            $controller->index();
        } else {
            echo "<h1>Selamat Datang di UPSKILL by MATLA</h1>";
            echo "<p>Status: Router aktif. Menunggu pembuatan HomeController.</p>";
        }
        break;

    case 'login':
        // Routing untuk autentikasi / login peserta & mentor
        $controllerFile = BASE_PATH . '/controllers/AuthController.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new AuthController();
            $controller->login();
        } else {
            echo "<h1>Halaman Login - UPSKILL by MATLA</h1>";
            echo "<p>Status: Router aktif. Menunggu pembuatan AuthController.</p>";
        }
        break;

    case 'courses':
        // Routing untuk eksplorasi katalog kursus
        $controllerFile = BASE_PATH . '/controllers/CourseController.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new CourseController();
            $controller->index();
        } else {
            echo "<h1>Katalog Kursus - UPSKILL by MATLA</h1>";
            echo "<p>Status: Router aktif. Menunggu pembuatan CourseController.</p>";
        }
        break;

    default:
        // Halaman 404 jika parameter 'page' tidak dikenali
        http_response_code(404);
        echo "<!DOCTYPE html>
        <html lang=\"id\">
        <head>
            <meta charset=\"UTF-8\">
            <title>404 - Halaman Tidak Ditemukan</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
                .card { background: #fff; padding: 2.5rem; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center; max-width: 420px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
                h1 { font-size: 2.5rem; margin: 0 0 0.5rem 0; color: #0f172a; }
                p { color: #64748b; margin-bottom: 1.5rem; font-size: 0.95rem; }
                a { display: inline-block; padding: 0.5rem 1rem; background: #0f172a; color: #fff; text-decoration: none; border-radius: 6px; font-size: 0.9rem; }
                a:hover { background: #334155; }
            </style>
        </head>
        <body>
            <div class=\"card\">
                <h1>404</h1>
                <p>Halaman yang Anda tuju (<code>" . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') . "</code>) tidak ditemukan.</p>
                <a href=\"index.php\">Kembali ke Beranda</a>
            </div>
        </body>
        </html>";
        break;
}
