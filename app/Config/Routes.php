<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Family Tree routes

$routes->get('/', 'Home::index');
$routes->match(['GET', 'POST'], '/save', 'FormController::save');

// Auth
$routes->match(['GET', 'POST'], '/register', 'Auth\Register::index');
$routes->match(['GET', 'POST'], '/register/sign', 'Auth\Register::sign');
$routes->match(['GET', 'POST'], 'auth/logout', 'Auth\Login::logout');
$routes->match(['GET', 'POST'], 'auth/login', 'Auth\Login::index');
$routes->match(['GET', 'POST'], 'auth/login/sign', 'Auth\Login::sign');
$routes->match(['GET', 'POST'], 'auth/password-reset', 'Auth\PasswordReset::index');
$routes->match(['GET', 'POST'], 'auth/password-forgot', 'Auth\PasswordReset::forgotPassword');
$routes->match(['GET', 'POST'], 'auth/process-forgot-password', 'Auth\PasswordReset::processForgotPassword');
$routes->match(['GET', 'POST'], 'auth/process-reset-password', 'Auth\PasswordReset::update_password');
$routes->get('auth/captcha_refresh', 'Auth\Login::refresh');

// Basic CRUD routes
$routes->get('family-tree', 'FamilyListController::index');
$routes->get('family-tree/create', 'FamilyListController::create');
$routes->post('family-tree/store', 'FamilyListController::store');
$routes->get('family-tree/edit/(:num)', 'FamilyListController::edit/$1');
$routes->post('family-tree/update/(:num)', 'FamilyListController::update/$1');
$routes->get('family-tree/delete/(:num)', 'FamilyListController::delete/$1');
$routes->post('family-tree/delete/(:num)', 'FamilyListController::delete/$1');
$routes->get('family-tree/chart', 'FamilyChartController::generateOrganizationalChart');

// Tree view and member selection routes
$routes->get('family-tree/get-tree', 'FamilyTreeController::getTree');
$routes->get('family-tree/get-tree/father', 'FamilyListController::getTree/father');
$routes->get('family-tree/get-tree/mother', 'FamilyListController::getTree/mother');
$routes->get('family-tree/get-tree/spouse', 'FamilyListController::getTree/spouse');
$routes->get('family-tree/view/(:num)', 'FamilyListController::view/$1');

// Member Dashboard
$routes->get('family-tree/dashboard', 'Dashboard::show');

// AJAX routes for dynamic loading
$routes->post('family-tree/get-member/(:num)', 'FamilyListController::getMember/$1');
$routes->post('family-tree/search-members', 'FamilyListController::searchMembers');

// Database Backup
$routes->get('database-backup', 'Database\DatabaseBackup::index');
$routes->post('database-backup/backup', 'Database\DatabaseBackup::backup');
$routes->get('database-backup/download/(:segment)', 'Database\DatabaseBackup::downloadBackup/$1');
$routes->post('database-backup/delete/(:segment)', 'Database\DatabaseBackup::delete/$1');

