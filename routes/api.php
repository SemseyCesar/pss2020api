<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

#AUTH Y USER
Route::post('/register','AuthController@register')->middleware('auth:api');
Route::post('/login','AuthController@login');
Route::post('/user/search','UserController@search')->middleware('auth:api');
Route::get('/user/docentes','UserController@docentes')->middleware('auth:api');
Route::post('/check','UserController@check')->middleware('auth:api');

#CARRERAS
Route::post('/carrera','CarreraController@store')->middleware('auth:api');
Route::post('/carrera/search','CarreraController@search')->middleware('auth:api');
Route::get('/carrera/{id}','CarreraController@detail')->middleware('auth:api');
Route::put('/carrera/{id}','CarreraController@update')->middleware('auth:api');
Route::delete('/carrera/{id}','CarreraController@delete')->middleware('auth:api');

#MATERIAS
Route::post('/materia','MateriaController@store')->middleware('auth:api');
Route::post('/materia/search','MateriaController@search');
Route::get('/materia/{id}','MateriaController@detail')->middleware('auth:api');
Route::get('/materia','MateriaController@index')->middleware('auth:api');
Route::put('/materia/{id}','MateriaController@update')->middleware('auth:api');
Route::delete('/materia/{id}','MateriaController@delete')->middleware('auth:api');
Route::post('/materia/asociar','MateriaController@asociar')->middleware('auth:api');
