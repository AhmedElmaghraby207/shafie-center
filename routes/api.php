<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'patient', 'namespace' => 'Api\v1\Patient'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/signup', 'AuthController@signup');
        Route::post('/login', 'AuthController@login');
        Route::post('/forgot_password', 'AuthController@forgotPassword');
        Route::post('/resend_email_verification', 'AuthController@resendEmailVerification');
        Route::post('/signup_facebook', 'AuthController@signupWithFacebook');
        Route::post('/signup_google', 'AuthController@signupWithGoogle');
        Route::post('/signup_apple', 'AuthController@signupWithApple');
    });

});
