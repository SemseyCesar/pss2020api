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

Route::get('/carrera/search','CarreraController@search');
Route::get('/materia/search','MateriaController@search');

Route::get('/carrera/{id}','CarreraController@detail');
Route::get('/materia/{id}','MateriaController@detail');
