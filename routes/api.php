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

Route::post('/register','AuthController@register');
Route::post('/login','AuthController@login');

Route::post('/carrera','CarreraController@store');
Route::post('/materia','MateriaController@store');

Route::post('/carrera/search','CarreraController@search');
Route::post('/materia/search','MateriaController@search');
Route::post('/user/search','UserController@search');


Route::get('/carrera/{id}','CarreraController@detail');
Route::get('/materia/{id}','MateriaController@detail');

Route::put('/carrera/{id}','CarreraController@update');
Route::put('/materia/{id}','MateriaController@update');

Route::delete('/carrera/{id}','CarreraController@delete');
Route::delete('/materia/{id}','MateriaController@delete');
