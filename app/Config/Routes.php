<?php

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Filters\Cors;
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('user',['except'=>'new,edit','filter'=>'cors']);
$routes->resource('products',['except'=>'new,edit,delete','filter'=>'cors']);
$routes->resource('image',['except'=>'new,edit','filter'=>'cors']);
$routes->resource('categories',['except'=>'new,edit','filter'=>'cors']);
$routes->resource('facture',['except'=>'new,edit','filter'=>'cors']);
$routes->post('auth','Login::auth',['filter'=>'cors']);
$routes->post('logout','Login::logout',['filter'=>'cors']);