<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an routerlication.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix'=>'v1'], function() use($router){
    $router->get('/candidates', 'CandidateController@index');
    $router->post('/candidates', 'CandidateController@create');
    $router->get('/candidates/{id:[0-9]+}', 'CandidateController@show');
    $router->get('/candidates/search', 'CandidateController@search');
    });
