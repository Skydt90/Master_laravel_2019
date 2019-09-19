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

use App\Comment;
use App\Mail\CommentPostedMarkdown;

Route::get('/', 'HomeController@home')->name('home'); //->middleWare('auth');
Route::get('/contact', 'HomeController@contact')->name('contact');
Route::get('/secret', 'HomeController@secret')->name('secret')->middleware('can:home.secret');

//Post routes
Route::resource('post', 'Posts\PostController'); //->middleWare('auth');
Route::resource('post.comments', 'Posts\PostCommentController')->only(['store']);
Route::get('/posts/tag/{tag}', 'Posts\PostTagController@index')->name('post.tags.index');

//User routes
Route::resource('user', 'Users\UserController')->only(['show', 'edit', 'update']);
Route::resource('user.comments', 'Users\UserCommentController')->only(['store']);

//Displaying a test mail in browser
Route::get('mailable', function () {
    $comment = Comment::find(1);
    return new CommentPostedMarkdown($comment);
});

Auth::routes();
