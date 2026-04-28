<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/home', 'Home::index');
$routes->get('/login', 'loginController::index');
$routes->get('/inscription', 'inscriptionController::index');
$routes->get('/ajouter', 'AjouterController::index');
$routes->get('/stat', 'StatController::index');
$routes->post('/ajouter/save', 'AjouterController::save');