<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// Route Definitions - UPSKILL by MATLA
// --------------------------------------------------------------------

// 1. Halaman Utama (Landing Page)
$routes->get('/', 'Home::index');


// 3. Autentikasi & Akun
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');

// 4. Eksplorasi & Pendaftaran Kursus
$routes->get('courses', 'Course::index');
$routes->get('courses/(:num)/enroll', 'Course::enroll/$1', ['filter' => 'auth']);
$routes->post('courses/(:num)/enroll', 'Course::enroll/$1', ['filter' => 'auth']);
$routes->get('courses/(:segment)', 'Course::detail/$1');

// 5. Dasbor Pengguna Reguler (Terproteksi Filter AuthGuard)
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard/account', 'Dashboard::account', ['filter' => 'auth']);
$routes->post('dashboard/account/update', 'Dashboard::updateAccount', ['filter' => 'auth']);
$routes->get('dashboard/course/(:num)', 'Dashboard::course/$1', ['filter' => 'auth']);
$routes->get('dashboard/learn/(:num)', 'Dashboard::learn/$1', ['filter' => 'auth']);
$routes->get('dashboard/access_material/(:num)', 'Dashboard::access_material/$1', ['filter' => 'auth']);
$routes->get('dashboard/quiz/(:num)', 'Quiz::cbt/$1', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'dashboard/mark_completed/(:num)', 'Dashboard::mark_completed/$1', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'dashboard/session/(:num)/complete', 'Dashboard::completeSession/$1', ['filter' => 'auth']);

// 5.1. Antarmuka Ujian CBT Peserta (Timer & Anti-Cheat)
$routes->get('sessions/(:num)/cbt', 'Quiz::cbt/$1');
$routes->post('sessions/(:num)/cbt/submit', 'Quiz::submitCbt/$1');

// 6. Area Khusus Super Admin (Terproteksi AuthGuard & RoleGuard:super_admin)
$routes->group('admin', ['filter' => ['auth', 'role:super_admin']], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Manajemen Kursus / Kelas
    $routes->get('courses', 'Admin\Course::index');
    $routes->get('courses/create', 'Admin\Course::create');
    $routes->post('courses/store', 'Admin\Course::store');
    $routes->get('courses/edit/(:num)', 'Admin\Course::edit/$1');
    $routes->post('courses/update/(:num)', 'Admin\Course::update/$1');
    $routes->get('courses/delete/(:num)', 'Admin\Course::delete/$1');

    // Manajemen Kurikulum Berbasis Sesi
    $routes->get('courses/(:num)/lessons', 'Admin\Lesson::index/$1');
    $routes->post('courses/(:num)/lessons/store', 'Admin\Lesson::store/$1');
    $routes->get('sessions/edit/(:num)', 'Admin\Lesson::edit/$1');
    $routes->post('sessions/update/(:num)', 'Admin\Lesson::update/$1');
    $routes->get('lessons/delete/(:num)', 'Admin\Lesson::delete/$1');

    // Manajemen Kuis & CBT
    $routes->get('sessions/(:num)/quiz', 'Admin\Quiz::index/$1');
    $routes->post('sessions/(:num)/quiz/store', 'Admin\Quiz::store/$1');
    $routes->post('sessions/(:num)/quiz/settings', 'Admin\Quiz::updateSettings/$1');
    $routes->get('sessions/(:num)/quiz/reset', 'Admin\Quiz::reset_attempts/$1');
    $routes->post('quiz/update/(:num)', 'Admin\Quiz::update/$1');
    $routes->get('quiz-questions/delete/(:num)', 'Admin\Quiz::delete/$1');

    // Laporan Nilai & Peringkat (Leaderboard)
    $routes->get('reports', 'Admin\Report::index');

    // Manajemen Pengguna
    $routes->get('users', 'Admin\User::index');
    $routes->get('users/create', 'Admin\User::create');
    $routes->post('users/store', 'Admin\User::store');
    $routes->get('users/edit/(:num)', 'Admin\User::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\User::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\User::delete/$1');
});
