<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('user',['except'=>'new,edit','filter'=>'cors']);
$routes->post('auth','Login::auth',['filter'=>'cors']);
$routes->post('logout','Login::logout',['filter'=>'cors']);