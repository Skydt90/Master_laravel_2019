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

Route::get('/', 'HomeController@home')->name('home'); //->middleWare('auth');
Route::get('/contact', 'HomeController@contact')->name('contact');
Route::get('/secret', 'HomeController@secret')->name('secret')->middleware('can:home.secret');

Route::resource('post', 'PostController'); //->middleWare('auth');
ROute::get('/posts/tag/{tag}', 'PostTagController@index')->name('post.tags.index');

Auth::routes();
