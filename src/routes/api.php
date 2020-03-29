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
    Route::get('', [
        'as' => 'hardware.index',
        'uses' => 'HardwareController@index'
    ]);
    Route::delete('{id}', [
        'as' => 'hardware.destroy',
        'uses' => 'HardwareController@destroy'
    ])->where('id', '[0-9]+');
    Route::post('', [
        'as' => 'hardware.store',
        'uses' => 'HardwareController@store'
    ]);
    Route::put('{id}', [
        'as' => 'hardware.update',
        'uses' => 'HardwareController@update'
    ])->where('id', '[0-9]+');
    Route::post('/lease', [
        'as' => 'hardware.lease',
        'uses' => 'HardwareController@lease'
    ]);
});

Route::group(['prefix' => 'system'], function () {
    Route::get('', [
        'as' => 'system.index',
        'uses' => 'SystemController@index'
    ]);
    Route::delete('{id}', [
        'as' => 'system.destroy',
        'uses' => 'SystemController@destroy'
    ])->where('id', '[0-9]+');
    Route::post('', [
        'as' => 'system.store',
        'uses' => 'SystemController@store'
    ]);
    Route::put('{id}', [
        'as' => 'system.update',
        'uses' => 'SystemController@update'
    ])->where('id', '[0-9]+');
});

Route::group(['prefix' => 'user'], function () {
    Route::get('', [
        'as' => 'user.index',
        'uses' => 'UserController@index'
    ]);
    Route::delete('{id}', [
        'as' => 'user.destroy',
        'uses' => 'UserController@destroy'
    ])->where('id', '[0-9]+');
    Route::post('', [
        'as' => 'user.store',
        'uses' => 'UserController@store'
    ]);
    Route::put('{id}', [
        'as' => 'user.update',
        'uses' => 'UserController@update'
    ])->where('id', '[0-9]+');
});

Route::group(['prefix' => 'transfer'], function () {
    Route::get('', [
        'as' => 'transfer.index',
        'uses' => 'TransferController@index'
    ]);
    Route::delete('{id}', [
        'as' => 'transfer.destroy',
        'uses' => 'TransferController@destroy'
    ])->where('id', '[0-9]+');
    Route::post('', [
        'as' => 'transfer.store',
        'uses' => 'TransferController@store'
    ]);
    Route::put('{id}', [
        'as' => 'transfer.update',
        'uses' => 'TransferController@update'
    ])->where('id', '[0-9]+');
});

Route::fallback(function () {
    throw new RouteNotFoundException('Błędny url');
});

