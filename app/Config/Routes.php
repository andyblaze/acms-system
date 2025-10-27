<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// cms updates
$routes->get('api/files/(:any)', 'Api::files/$1');
// Default home page
$routes->get('/', 'Home::index');
// Catch-all for all other URLs
$routes->get('(:any)', 'Home::index/$1');
