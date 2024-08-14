<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index/$1');
    $routes->get('manajementuser', 'AdminController::manajementuser/$1');
    $routes->post('updateUser/(:num)', 'AdminController::updateUser/$1');
    $routes->get('profile/(:num)', 'AdminController::getUserData/$1');
    $routes->post('addUser', 'AdminController::addUser');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('block', 'AdminController::block');

    // Tambahkan rute admin lain di sini
});

$routes->group('user', ['filter' => 'user'], function ($routes) {
    $routes->get('dashboard', 'UserController::index/$1');
    $routes->get('profile', 'UserController::userprofile');
    $routes->post('updateUser/(:num)', 'UserController::updateUser/$1');
    $routes->get('absen', 'UserController::absen');
    $routes->get('lokasi', 'UserController::lokasi');
    $routes->post('submit_absen', 'UserController::submit_absen');
    $routes->get('cek_jam_masuk', 'UserController::cek_jam_masuk');
    $routes->get('block', 'UserController::block');
    // Tambahkan rute user lain di sini
});
$routes->set404Override('BlockAkses::show404');
$routes->get('/', 'AuthController::login');
$routes->post('/', 'AuthController::loginAuth');
$routes->set404Override(function () {
    echo view('block_akses');
});
