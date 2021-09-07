<?php

use Illuminate\Http\Request;

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


Route::post('/login', 'Auth\AuthController@login');
Route::post('/user', 'UsuarioController@store');


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', 'Auth\AuthController@logout');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UsuarioController@get');
        Route::get('/{usuario_id}', 'UsuarioController@get');
        Route::put('{usuario_id}', 'UsuarioController@update');
        Route::delete('{usuario_id}', 'UsuarioController@delete');
    });

    Route::group(['prefix' => 'setor'], function () {
        Route::get('/', 'Processos\SetorController@get');
        Route::get('/{setor_id}', 'Processos\SetorController@get');
        Route::post('/', 'Processos\SetorController@store');
        Route::put('{setor_id}', 'Processos\SetorController@update');
        Route::delete('{setor_id}', 'Processos\SetorController@delete');
    });

    Route::group(['prefix' => 'processo'], function () {
        Route::get('/', 'Processos\ProcessoController@get');
        Route::get('/{processo_id}', 'Processos\ProcessoController@get');
        Route::post('/', 'Processos\ProcessoController@store');
        Route::put('{processo_id}', 'Processos\ProcessoController@update');
        Route::delete('{processo_id}', 'Processos\ProcessoController@delete');
    });
});
