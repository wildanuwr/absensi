<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('manajementuser', 'AdminController::manajementuser');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('block', 'AdminController::block');

    // Tambahkan rute admin lain di sini
});

$routes->group('user', ['filter' => 'user'], function ($routes) {
    $routes->get('dashboard', 'UserController::index/$1');
    $routes->get('profile', 'UserController::userprofile/');
    $routes->get('block', 'UserController::block');
    // Tambahkan rute user lain di sini
});

$routes->get('/', 'AuthController::login');
$routes->post('/', 'AuthController::loginAuth');
