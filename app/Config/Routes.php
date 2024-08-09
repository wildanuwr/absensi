<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::login');
$routes->post('/', 'AuthController::loginAuth');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/user', 'UserController::index');
$routes->get('profile', 'Home::userprofile');
$routes->get('/admin', 'AdminController::index');
$routes->get('/admin/manajementuser', 'AdminController::manajementuser');
$routes->get('/admin', 'AdminController::index', ['filter' => 'rolecheck:1']);
$routes->get('/user', 'UserController::index', ['filter' => 'rolecheck:2']);
