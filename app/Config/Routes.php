<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('home/files/(:any)', 'Home::files/$1');
$routes->get('/', 'Home::index');
