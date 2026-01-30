<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');


// ================= AUTH =================

// Manual login
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');

// Register
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::storeRegister');

// Logout
$routes->get('/logout', 'Auth::logout');

// 🔥 Google OAuth Login
$routes->get('/auth/google', 'Auth::google');
$routes->get('/auth/google/callback', 'Auth::googleCallback');


// ================= PROTECTED AREA =================

$routes->get('/orders', 'Orders::index', ['filter' => 'auth']);

// Kalau nanti dashboard dipakai lagi:
// $routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
