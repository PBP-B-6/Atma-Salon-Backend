<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyEmailController;

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
// Verify email
Route::get('verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::get('user', 'Api\AuthController@index');
Route::get('user/{id}', 'Api\AuthController@show');
Route::post('user', 'Api\AuthController@register');
Route::put('user/{id}', 'Api\AuthController@update');
Route::delete('user/{id}', 'Api\AuthController@destroy');

Route::get('order/{id}', 'Api\OrderController@index');
// Route::get('order/{id}', 'Api\OrderController@show');
Route::post('order/{id}', 'Api\OrderController@store');
Route::put('order/{id}', 'Api\OrderController@update');
Route::delete('order/{id}', 'Api\OrderController@destroy');

Route::get('testimoni', 'Api\TestimoniController@index');
Route::get('testimoni/{id}', 'Api\TestimoniController@show');
Route::post('testimoni', 'Api\TestimoniController@store');
Route::put('testimoni/{id}', 'Api\TestimoniController@update');
Route::delete('testimoni/{id}', 'Api\TestimoniController@destroy');


Route::group(['middleware' => 'auth:sanctum'], function(){
    
});