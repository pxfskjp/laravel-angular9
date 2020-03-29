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

Route::post('register', [
    'as' => 'register',
    'uses' => 'AuthController@register'
]);

Route::get('confirm-register/{token}', [
    'as' => 'confirm.register',
    'uses' => 'AuthController@confirmRegister'
]);

Route::post('login', [
    'as' => 'login',
    'uses' => 'AuthController@login'
]);

Route::post('logout', [
    'as' => 'logout',
    'uses' => 'AuthController@logout'
]);

Route::get('ping', [
    'as' => 'ping',
    'uses' => 'AuthController@pingUser'
]);

Route::post('remind', [
    'as' => 'password.remind',
    'uses' => 'AuthController@remindPassword'
]);

Route::post('password-save', [
    'as' => 'password.save',
    'uses' => 'AuthController@savePassword'
]);

Route::fallback(function () {
    throw new RouteNotFoundException('Błędny url');
});

