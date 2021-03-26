<?php

use Illuminate\Support\Facades\Route;

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


Route::group(['namespace' => 'Dashboard'], function () {
    //change languages
    Route::get('/changeLanguage/{lang}', 'LanguagesController@changeLanguage')->name('CHANGE_LANGUAGE')->middleware('lang');

    //get email template page
    Route::get('/email-temp-view', 'HomeController@emailTempView');

    //dashboard page
    Route::get('/', 'HomeController@dashboard');
    Route::get('/dashboard', 'HomeController@dashboard');

    //admin auth
    Route::get('/login', 'AuthController@get_login')->name('admin.get_login');
    Route::post('/login', 'AuthController@post_login')->name('admin.post_login');
    Route::get('/logout', 'AuthController@logout')->name('admin.logout');
    Route::get('/password/forgot', 'AuthController@getForgotPassword')->name('admin.get_forgot');
    Route::post('/password/forgot', 'AuthController@postForgotPassword')->name('admin.post_forgot');
    Route::get('/password/reset/{token}', 'AuthController@getResetPassword')->name('admin.get_reset');
    Route::post('/password/reset', 'AuthController@postResetPassword')->name('admin.post_reset');

    Route::group(['prefix' => 'dashboard'], function () {
        //admin pages
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/list', 'AdminsController@list')->name('admin.list');
            Route::post('/get-admins-data', 'AdminsController@getAdmins')->name('admin.list_data');
            Route::get('/create', 'AdminsController@create')->name('admin.create');
            Route::post('/store', 'AdminsController@store')->name('admin.store');
            Route::get('/{id}/edit', 'AdminsController@edit')->name('admin.edit');
            Route::post('/{id}/update', 'AdminsController@update')->name('admin.update');
            Route::get('/{id}/delete', 'AdminsController@delete')->name('admin.delete');
        });

        //patient pages
        Route::group(['prefix' => 'patient'], function () {
            Route::get('/list', 'PatientsController@list')->name('patient.list');
            Route::post('/get-patients-data', 'PatientsController@getPatients')->name('patient.list_data');
            Route::get('/create', 'PatientsController@create')->name('patient.create');
            Route::post('/store', 'PatientsController@store')->name('patient.store');
            Route::get('/{id}/edit', 'PatientsController@edit')->name('patient.edit');
            Route::post('/{id}/update', 'PatientsController@update')->name('patient.update');
            Route::get('/{id}/delete', 'PatientsController@delete')->name('patient.delete');
        });

        //announcements
        Route::group(['prefix' => 'announcement'], function () {
            Route::get('/create', 'AnnouncementsController@create')->name('announcement.create');
            Route::post('/send', 'AnnouncementsController@send')->name('announcement.send');
        });
    });

});

Route::group(['prefix' => 'patient', 'namespace' => 'Patient'], function () {
    Route::get('/email/verify/{token}', 'AuthController@verify_email');
    Route::get('/password/reset/{token}', 'AuthController@get_reset');
    Route::post('/password/reset', 'AuthController@post_reset');
});

