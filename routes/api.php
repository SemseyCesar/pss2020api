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
Route::post('/register','AuthController@register');
Route::post('/login','AuthController@login');
Route::post('/user/search','UserController@search');
Route::get('/user/docentes','UserController@docentes');

#CARRERAS
Route::post('/carrera','CarreraController@store')->middleware('auth:api');
Route::post('/carrera/search','CarreraController@search');
Route::get('/carrera/{id}','CarreraController@detail')->middleware('auth:api');
Route::put('/carrera/{id}','CarreraController@update')->middleware('auth:api');
Route::delete('/carrera/{id}','CarreraController@delete')->middleware('auth:api');

#MATERIAS
Route::post('/materia','MateriaController@store')->middleware('auth:api');
Route::post('/materia/search','MateriaController@search');
Route::get('/materia/{id}','MateriaController@detail')->middleware('auth:api');
Route::get('/materia','MateriaController@index');
Route::put('/materia/{id}','MateriaController@update')->middleware('auth:api');
Route::delete('/materia/{id}','MateriaController@delete')->middleware('auth:api');
Route::post('/materia/asociar','MateriaController@asociar');
