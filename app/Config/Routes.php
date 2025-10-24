<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('home/files/(:any)/(:any)', 'Home::files/$3/$4');
$routes->get('/', 'Home::index');
