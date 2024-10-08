<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index/$1');
    $routes->get('manajementuser', 'AdminController::manajementuser/$1');
    $routes->get('manajementlokasi', 'AdminController::manajementlokasi');
    $routes->get('download-template', 'AdminController::downloadTemplate');
    $routes->post('import-excel', 'AdminController::importExcel');
    $routes->post('simpanlokasi', 'AdminController::simpanlokasi');
    $routes->post('updateUser/(:num)', 'AdminController::updateUser/$1');
    $routes->get('profile/(:num)', 'AdminController::getUserData/$1');
    $routes->get('penempatan', 'AdminController::penempatan');
    $routes->post('updatePenempatan', 'AdminController::updatePenempatan/$1');
    $routes->post('addUser', 'AdminController::addUser');
    $routes->get('deleteUser/(:num)', 'AdminController::deleteUser/$1');
    $routes->get('laporanabsensi', 'AdminController::laporanabsensi');
    $routes->get('download-laporan', 'AdminController::download_laporan');
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
    $routes->get('izin', 'UserController::izin');
    $routes->get('logout', 'AuthController::logout');
    $routes->post('submit_izin', 'UserController::submit_izin');
    $routes->get('user_history', 'UserController::history/$1');
    $routes->post('submit_absen', 'UserController::submit_absen');
    $routes->get('cek_jam_masuk', 'UserController::cek_jam_masuk');
    $routes->get('block', 'UserController::block');
    // Tambahkan rute user lain di sini
});
$routes->set404Override('BlockAkses::show404');
$routes->get('home/redirectToDashboard', 'BlockAkses::redirectToDashboard');
$routes->get('/', 'AuthController::login');
$routes->post('/', 'AuthController::loginAuth');
$routes->set404Override(function () {
    echo view('block_akses');
});
