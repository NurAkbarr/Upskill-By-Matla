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

// 4. Eksplorasi & Katalog Kursus
$routes->get('courses', 'Course::index');
$routes->get('courses/(:segment)', 'Course::detail/$1');

// 5. Dasbor Pengguna Reguler (Terproteksi Filter AuthGuard)
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// 6. Area Khusus Super Admin (Terproteksi AuthGuard & RoleGuard:super_admin)
$routes->group('admin', ['filter' => ['auth', 'role:super_admin']], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Manajemen Kursus / Kelas
    $routes->get('courses', 'Admin\Course::index');
    $routes->get('courses/create', 'Admin\Course::create');
    $routes->post('courses/store', 'Admin\Course::store');
    $routes->get('courses/delete/(:num)', 'Admin\Course::delete/$1');

    // Manajemen Pengguna
    $routes->get('users', 'Admin\User::index');
    $routes->get('users/create', 'Admin\User::create');
    $routes->post('users/store', 'Admin\User::store');
    $routes->get('users/edit/(:num)', 'Admin\User::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\User::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\User::delete/$1');
});
