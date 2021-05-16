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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['prefix' => 'admin', 'namespace' => "Admin"], function () {
  Route::get('/', 'AdminAuth\LoginController@showLoginForm');
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('admin_login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('admin.password.reset');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'chef', 'namespace' => "Chef"], function () {
  Route::get('/', 'ChefAuth\LoginController@showLoginForm');
  Route::get('/login', 'ChefAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'ChefAuth\LoginController@login');
  Route::post('/logout', 'ChefAuth\LoginController@logout');

  Route::get('/register', 'ChefAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'ChefAuth\RegisterController@register');

  Route::post('/password/email', 'ChefAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'ChefAuth\ResetPasswordController@reset')->name('chef.password.reset');
  Route::get('/password/reset', 'ChefAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'ChefAuth\ResetPasswordController@showResetForm');
});


//front end routes
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::post('/logout', 'Auth\LoginController@logout');
Route::group(['namespace' => "Front"], function () {
  Route::get('/', 'HomeController@index');
  Route::get('/privacy-policies', 'HomeController@privacy');
  Route::get('/home', 'HomeController@index');
  Route::get('/payment-success', 'HomeController@paymentSuccess');
  Route::get('/payment-error', 'HomeController@paymentError');
});

