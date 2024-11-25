<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('api/videos', 'Videos::index');
$routes->get('api/videos/(:any)', 'Videos::show/$1'); // Route for fetching a specific video by ID
