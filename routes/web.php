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

Route::get('/', function () {
    return redirect()->route('home');
});

Route::group(['namespace' => 'Dashboard', 'prefix' => 'shafie-center'], function () {
    //change languages
    Route::get('/changeLanguage/{lang}', 'LanguagesController@changeLanguage')->name('CHANGE_LANGUAGE')->middleware('lang');

    //get email template page
    Route::get('/email-temp-view', 'HomeController@emailTempView');

    //dashboard page
    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('home');

    //admin auth
    Route::get('/login', 'AuthController@get_login')->name('admin.get_login');
    Route::post('/login', 'AuthController@post_login')->name('admin.post_login');
    Route::get('/logout', 'AuthController@logout')->name('admin.logout');
    Route::get('/password/forgot', 'AuthController@getForgotPassword')->name('admin.get_forgot');
    Route::post('/password/forgot', 'AuthController@postForgotPassword')->name('admin.post_forgot');
    Route::get('/password/reset/{token}', 'AuthController@getResetPassword')->name('admin.get_reset');
    Route::post('/password/reset', 'AuthController@postResetPassword')->name('admin.post_reset');

    Route::group(['prefix' => 'dashboard'], function () {
        //role pages
        Route::group(['prefix' => 'role'], function () {
            Route::get('/index', 'RolesController@index')->name('role.index');
            Route::post('/list', 'RolesController@list')->name('role.list');
            Route::get('/create', 'RolesController@create')->name('role.create');
            Route::post('/store', 'RolesController@store')->name('role.store');
            Route::get('/{id}/edit', 'RolesController@edit')->name('role.edit');
            Route::post('/{id}/update', 'RolesController@update')->name('role.update');
            Route::get('/{id}/delete', 'RolesController@destroy')->name('role.delete');
        });

        //admin pages
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/index', 'AdminsController@index')->name('admin.index');
            Route::post('/list', 'AdminsController@list')->name('admin.list');
            Route::get('/create', 'AdminsController@create')->name('admin.create');
            Route::post('/store', 'AdminsController@store')->name('admin.store');
            Route::get('/{id}/show', 'AdminsController@show')->name('admin.show');
            Route::get('/{id}/edit', 'AdminsController@edit')->name('admin.edit');
            Route::post('/{id}/update', 'AdminsController@update')->name('admin.update');
            Route::get('/{id}/delete', 'AdminsController@destroy')->name('admin.delete');
        });

        //doctor pages
        Route::group(['prefix' => 'doctor'], function () {
            Route::get('/show', 'DoctorController@show')->name('doctor.show');
            Route::get('/edit', 'DoctorController@edit')->name('doctor.edit');
            Route::post('/{id}/update', 'DoctorController@update')->name('doctor.update');
        });

        //patient pages
        Route::group(['prefix' => 'patient'], function () {
            Route::get('/index', 'PatientsController@index')->name('patient.index');
            Route::post('/list', 'PatientsController@list')->name('patient.list');
            Route::get('/create', 'PatientsController@create')->name('patient.create');
            Route::post('/store', 'PatientsController@store')->name('patient.store');
            Route::get('/{id}/show', 'PatientsController@show')->name('patient.show');
            Route::get('/{id}/edit', 'PatientsController@edit')->name('patient.edit');
            Route::get('/{id}/activate', 'PatientsController@activate')->name('patient.activate');
            Route::get('/{id}/deactivate', 'PatientsController@deactivate')->name('patient.deactivate');
            Route::post('/{id}/update', 'PatientsController@update')->name('patient.update');
            Route::get('/{id}/delete', 'PatientsController@destroy')->name('patient.delete');
        });

        //case pages
        Route::group(['prefix' => 'case'], function () {
            Route::get('/index', 'CasesController@index')->name('case.index');
            Route::post('/list', 'CasesController@list')->name('case.list');
            Route::get('/create', 'CasesController@create')->name('case.create');
            Route::post('/store', 'CasesController@store')->name('case.store');
            Route::get('/{id}/edit', 'CasesController@edit')->name('case.edit');
            Route::post('/{id}/update', 'CasesController@update')->name('case.update');
            Route::get('/{id}/delete', 'CasesController@destroy')->name('case.delete');
        });

        //announcements
        Route::group(['prefix' => 'announcement'], function () {
            Route::get('/create', 'AnnouncementsController@create')->name('announcement.create');
            Route::post('/send', 'AnnouncementsController@send')->name('announcement.send');
        });

        //messages
        Route::group(['prefix' => 'message'], function () {
            Route::get('/index', 'MessagesController@index')->name('message.index');
            Route::post('/list', 'MessagesController@list')->name('message.list');
            Route::get('/{id}/read', 'MessagesController@read')->name('message.read');
            Route::get('/{id}/delete', 'MessagesController@destroy')->name('message.delete');
        });

        //faq pages
        Route::group(['prefix' => 'faq'], function () {
            Route::get('/index', 'FaqsController@index')->name('faq.index');
            Route::post('/list', 'FaqsController@list')->name('faq.list');
            Route::get('/create', 'FaqsController@create')->name('faq.create');
            Route::post('/store', 'FaqsController@store')->name('faq.store');
            Route::get('/{id}/edit', 'FaqsController@edit')->name('faq.edit');
            Route::post('/{id}/update', 'FaqsController@update')->name('faq.update');
            Route::get('/{id}/delete', 'FaqsController@destroy')->name('faq.delete');
        });

        //operation pages
        Route::group(['prefix' => 'operation'], function () {
            Route::get('/index', 'OperationsController@index')->name('operation.index');
            Route::post('/list', 'OperationsController@list')->name('operation.list');
            Route::get('/create', 'OperationsController@create')->name('operation.create');
            Route::post('/store', 'OperationsController@store')->name('operation.store');
            Route::get('/{id}/edit', 'OperationsController@edit')->name('operation.edit');
            Route::post('/{id}/update', 'OperationsController@update')->name('operation.update');
            Route::get('/{id}/delete', 'OperationsController@destroy')->name('operation.delete');
        });

        //notification_template pages
        Route::group(['prefix' => 'notification_template'], function () {
            Route::get('/index', 'NotificationTemplatesController@index')->name('notification_template.index');
            Route::post('/list', 'NotificationTemplatesController@list')->name('notification_template.list');
            Route::get('/{id}/edit', 'NotificationTemplatesController@edit')->name('notification_template.edit');
            Route::post('/{id}/update', 'NotificationTemplatesController@update')->name('notification_template.update');
        });

        //branch pages
        Route::group(['prefix' => 'branch'], function () {
            Route::get('/index', 'BranchesController@index')->name('branch.index');
            Route::post('/list', 'BranchesController@list')->name('branch.list');
            Route::get('/create', 'BranchesController@create')->name('branch.create');
            Route::post('/store', 'BranchesController@store')->name('branch.store');
            Route::get('/{id}/edit', 'BranchesController@edit')->name('branch.edit');
            Route::post('/{id}/update', 'BranchesController@update')->name('branch.update');
            Route::get('/{id}/delete', 'BranchesController@destroy')->name('branch.delete');
        });

        //settings
        Route::group(['prefix' => 'setting'], function () {
            Route::get('/index', 'SettingsController@index')->name('setting.index');
            Route::post('/list', 'SettingsController@list')->name('setting.list');
            Route::get('/{id}/edit', 'SettingsController@edit')->name('setting.edit');
            Route::post('/{id}/update', 'SettingsController@update')->name('setting.update');
        });
    });

});

Route::group(['prefix' => 'patient', 'namespace' => 'Patient'], function () {
    Route::get('/email/verify/{token}', 'AuthController@verify_email');
    Route::get('/password/reset/{token}', 'AuthController@get_reset');
    Route::post('/password/reset', 'AuthController@post_reset');
});

