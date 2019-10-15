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

//this applies the api middleware to everything in this file
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//add v1 as prefix after api. 
//name is name of the api and name below is name of the endpoint
//namespace is controller location
Route::prefix('v1')->name('api.v1.')->namespace('Api\V1')->group(function() {
    Route::get('/status', function() {
        return response()->json(['status' => 200]);
    })->name('status');
    Route::apiResource('post.comments', 'PostCommentController');
});

Route::prefix('v2')->name('api.v2.')->group(function() {
    Route::get('/status', function() {
        return response()->json(['status' => true]);
    });
});

Route::fallback(function() {
    return response()->json([
        'message' => 'Not found'
    ], 404);
})->name('api.fallback');
