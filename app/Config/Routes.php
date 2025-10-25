<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('api/files/(:any)', 'Api::files/$1');
$routes->get('/', 'Home::index');
