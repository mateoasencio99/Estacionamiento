<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\SensorEstado;

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/actualizar_estado', 'HomeController@store');
Route::post('/actualizar_estado', 'HomeController@store');


Route::post('/sensor-status', function () {
    $sensorEstado = SensorEstado::latest()->first(); // Obtén el último estado del sensor
    return response()->json($sensorEstado);
});
