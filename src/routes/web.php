<?php
/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

Route::view('/', 'web::index', ['baseRef' => '/']);
Route::view('/login', 'web::index', ['baseRef' => '/']);

Route::view('/hardware', 'web::index', ['baseRef' => '/']);
Route::view('/software', 'web::index', ['baseRef' => '/']);
Route::view('/user', 'web::index', ['baseRef' => '/']);
Route::view('/history', 'web::index', ['baseRef' => '/']);

Route::post('register', 'AuthController@register')->name('register');
Route::get('confirm-register/{token}', 'AuthController@confirmRegister')->name('confirm.register');
Route::post('login', 'AuthController@login')->name('login');
Route::post('logout', 'AuthController@logout')->name('logout');
Route::get('ping', 'AuthController@pingUser')->name('ping');
Route::post('remind', 'AuthController@remindPassword')->name('password.remind');
Route::post('password-save', 'AuthController@savePassword')->name('password.save');

Route::fallback(function () {
    throw new RouteNotFoundException('Błędny url');
});

