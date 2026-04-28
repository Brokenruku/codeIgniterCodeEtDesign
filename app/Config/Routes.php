<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'loginController::index');
$routes->get('/login', 'loginController::index');
$routes->post('/login/authenticate', 'loginController::authenticate');
$routes->get('/inscription', 'inscriptionController::index');
$routes->post('/inscription/register', 'inscriptionController::register');
$routes->get('/home', 'Home::index');
$routes->get('/logout', 'Home::logout');
$routes->get('/ajouter', 'AjouterController::index');
$routes->post('/ajouter/save', 'AjouterController::save');
$routes->get('/stat', 'StatController::index');