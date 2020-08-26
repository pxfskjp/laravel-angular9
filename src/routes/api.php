<?php
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
Route::post('/refresh-token', 'AuthController@refreshToken');

Route::group(['prefix' => 'hardware'], function () {
    Route::get('', 'HardwareController@index')->name('hardware.index');
    Route::delete('{id}', 'HardwareController@destroy')->where('id', '[0-9]+')->name('hardware.destroy');
    Route::post('', 'HardwareController@store')->name('hardware.store');
    Route::put('{id}', 'HardwareController@update')->where('id', '[0-9]+')->name('hardware.update');
    Route::post('/lease', 'HardwareController@lease')->name('hardware.lease');
});

Route::group(['prefix' => 'system'], function () {
    Route::get('', 'SystemController@index')->name('system.index');
    Route::delete('{id}', 'SystemController@destroy')->where('id', '[0-9]+')->name('system.destroy');
    Route::post('', 'SystemController@store')->name('system.store');
    Route::put('{id}', 'SystemController@update')->where('id', '[0-9]+')->name('system.update');
});

Route::group(['prefix' => 'user'], function () {
    Route::get('', 'UserController@index')->name('user.index');
    Route::delete('{id}', 'UserController@destroy')->where('id', '[0-9]+')->name('user.destroy');
    Route::post('', 'UserController@store')->name('user.store');
    Route::put('{id}', 'UserController@update')->where('id', '[0-9]+')->name('user.update');
});

Route::group(['prefix' => 'transfer'], function () {
    Route::get('', 'TransferController@index')->name('transfer.index');
    Route::delete('{id}', 'TransferController@destroy')->where('id', '[0-9]+')->name('transfer.destroy');
    Route::post('', 'TransferController@store')->name('transfer.store');
    Route::put('{id}', 'TransferController@update')->where('id', '[0-9]+')->name('transfer.update');
});

Route::fallback(function () {
    throw new RouteNotFoundException('Błędny url');
});

