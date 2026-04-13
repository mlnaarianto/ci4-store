<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// ================= AUTH =================

// Login
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');

// Register
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::storeRegister');

// Logout
$routes->get('/logout', 'Auth::logout');

// Google OAuth
$routes->get('/auth/google', 'Auth::google');
$routes->get('/auth/google/callback', 'Auth::googleCallback');

// Serve avatar
$routes->get('avatar/(:segment)', 'Auth::serveAvatar/$1');




// =====================================================
// ================= USER AREA (WAJIB LOGIN) ===========
// =====================================================
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Orders
    $routes->get('/orders', 'Orders::index');

    // ================= ACCOUNT SETTING =================
    $routes->get('account/setting', 'SettingAccount::index');
    $routes->post('account/setting/update', 'SettingAccount::update');

    // ================= CONFIRM =================
    $routes->group('confirm', function ($routes) {
        $routes->get('/', 'Confirm::index');
        $routes->post('store', 'Confirm::store');
        $routes->post('update/(:num)', 'Confirm::update/$1');
        $routes->post('delete/(:num)', 'Confirm::delete/$1');

        // Proxy image secure
        $routes->get('image/(:segment)/(:segment)', 'Confirm::image/$1/$2');
    });

    // ================= STORE =================
    $routes->group('store', function ($routes) {

        // PENTING: Route untuk image harus PERTAMA (sebelum route lain)
        $routes->get('image/(:segment)/(:segment)', 'Store::image/$1/$2');

        // TAMBAHKAN: Route untuk refresh image (dengan cache busting)
        $routes->get('refresh-image/(:segment)/(:segment)', 'Store::refreshImage/$1/$2');

        // Baru setelah itu route CRUD biasa
        $routes->get('/', 'Store::index');
        $routes->get('create', 'Store::create');
        $routes->post('store', 'Store::store');
        $routes->get('edit/(:num)', 'Store::edit/$1');
        $routes->post('update/(:num)', 'Store::update/$1');
        $routes->get('delete/(:num)', 'Store::delete/$1'); // Optional
    });

    // ================= CHAT (USER) =================
    $routes->group('chat', function ($routes) {
        $routes->get('/', 'Chat::index'); // /chat
    });
});


// =====================================================
// ================= ADMIN AREA ========================
// =====================================================

$routes->group('admin', ['filter' => 'admin'], function ($routes) {

    // 🔥 CHAT ADMIN
    $routes->get('chat', 'Chat::index');
    // $routes->get('chat/(:segment)', 'Chat::room/$1');
    



    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Produk
    $routes->get('produk', 'AdminProduk::index');

    // Users
    $routes->get('users', 'AdminUsers::index');

    // ================= CONFIRM APPROVAL =================
    $routes->get('confirm', 'ApproveConfirm::index');
    $routes->post('confirm/approve/(:num)', 'ApproveConfirm::approve/$1');
    $routes->post('confirm/reject/(:num)', 'ApproveConfirm::reject/$1');

    // ================= STATUS ACCOUNT =================
    $routes->get('status', 'StatusAccount::index');
    $routes->post('status/nonaktifkan/(:num)', 'StatusAccount::nonaktifkan/$1');
    $routes->post('status/aktifkan/(:num)', 'StatusAccount::aktifkan/$1');


    // ================= STORE APPROVAL =================
    $routes->get('stores', 'StatusStoreController::index');
    $routes->get('stores/edit/(:num)', 'StatusStoreController::edit/$1');
    $routes->post('stores/update/(:num)', 'StatusStoreController::update/$1');
    $routes->get('stores/approve/(:num)', 'StatusStoreController::approve/$1');
    $routes->post('stores/reject/(:num)', 'StatusStoreController::reject/$1');
});
