<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// API
$router->post('/api/login', 'Admin\AuthController@login');

$router->get('/api/category', 'Admin\CategoryController@index');
$router->get('/api/category/select', 'Admin\CategoryController@select');
$router->get('/api/category/paginate', 'Admin\CategoryController@paginate');
$router->get('/api/category/paginateshow/{id}', 'Admin\CategoryController@paginate_show');
$router->post('/api/category', 'Admin\CategoryController@store');
$router->post('/api/category/file/{id}', 'Admin\CategoryController@upload');
$router->put('/api/category/{id}', 'Admin\CategoryController@update');
$router->get('/api/category/{id}', 'Admin\CategoryController@show');
$router->delete('/api/category/{id}', 'Admin\CategoryController@destroy');

$router->get('/api/product', 'Admin\ProductController@index');
$router->get('/api/product/paginate/{id}', 'Admin\ProductController@paginate');
$router->post('/api/product', 'Admin\ProductController@store');
$router->post('/api/product/file/{id}', 'Admin\ProductController@upload');
$router->put('/api/product/{id}', 'Admin\ProductController@update');
$router->get('/api/product/{id}', 'Admin\ProductController@show');
$router->delete('/api/product/{id}', 'Admin\ProductController@destroy');

// ROUTE CLIENT
$router->get('/{route:.*}/', function ()  {
    return view('layouts.app');
});


