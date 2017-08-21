<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| owhfgbewfolhhiwz
*/


Route::get('login.html', function() {
    return view('pages.login', ['title' => 'Авторизация пользователя']);
})->name('login');

Route::post('login.html', 'Auth\LoginController@login')->name('login.action');
Route::post('logout.html', 'Auth\LoginController@logout')->name('logout.action');

Route::group(['middleware' => 'auth'], function() {

    Route::get('/', 'HomeController@home')->name('page.home');

    // Document routes
    Route::group(['prefix' => 'document'], function() {
        Route::get('list.html', 'DocumentsController@index')->name('page.document.list');
        Route::get('create.html', 'DocumentsController@create')->name('page.document.create');
        Route::get('{document}/show.html', 'DocumentsController@show')->name('page.document.show');
        Route::post('store.html', 'DocumentsController@store')->name('page.document.store');
        Route::post('{document}/copy.html', 'DocumentsController@copy')->name('page.document.copy');
        Route::post('{document}/approve/add.html', 'DocumentsController@approve_add')->name('page.document.approve.add');
        Route::post('{document}/update.html', 'DocumentsController@update')->name('page.document.update');
        Route::post('{document}/approve/{documentApprove}/answer.html', 'DocumentsController@approve_answer')->name('page.document.approve.answer');
        Route::post('{document}/task/set.html', 'DocumentsController@set_task')->name('page.document.task.set');
    });

    Route::group(['prefix' => 'expertise'], function() {
        Route::get('list.html', 'ExpertisesController@index')->name('page.expertise.list');
        Route::get('create.html', 'ExpertisesController@create')->name('page.expertise.create');
        Route::get('{expertise}/show.html', 'ExpertisesController@show')->name('page.expertise.show');
        Route::post('store.html', 'ExpertisesController@store')->name('page.expertise.store');
        Route::post('specialities.html', 'ExpertisesController@specialities')->name('page.expertise.specialities');
        Route::post('agencies.html', 'ExpertisesController@agencies')->name('page.expertise.agencies');
    });

    Route::get('file/{file}/download.html', 'FilesController@download')->name('page.file.download');

    Route::group(['prefix' => 'correspondence'], function() {
        Route::get('{type}/list.html', 'CorrespondencesController@index')
            ->where(['type' => '[a-z]+'])->name('page.correspondence.list');
        Route::get('{correspondence}/show.html', 'CorrespondencesController@show')->name('page.correspondence.show');
        Route::get('income/create.html', 'CorrespondencesController@create')->name('page.correspondence.income.create');
        Route::get('outcome/{document}/create.html', 'CorrespondencesController@create_outcome')->name('page.correspondence.outcome.create');
        Route::post('{correspondence}/register.html', 'CorrespondencesController@edit')->name('page.correspondence.edit');
        Route::post('store.html', 'CorrespondencesController@store')->name('page.correspondence.store');
        Route::post('outcome/store.html', 'CorrespondencesController@store_outcome')->name('page.correspondence.store.outcome');
        Route::post('correspondence.html', 'CorrespondencesController@correspondence')->name('page.correspondence');
        Route::post('correspondent.html', 'CorrespondentsController@get')->name('page.correspondence.correspondent');
    });

    Route::get('structure.html', 'UsersController@index')->name('page.structure');
    Route::group(['prefix' => 'structure'], function() {
        Route::get('department/create.html', 'DepartmentsController@department_create')->name('page.department.create');
        Route::post('department/store.html', 'DepartmentsController@department_store')->name('page.department.store');

        Route::get('subdivision/create.html', 'DepartmentsController@subdivision_create')->name('page.subdivision.create');
        Route::post('subdivision/store.html', 'DepartmentsController@subdivision_store')->name('page.subdivision.store');
    });

    Route::group(['prefix' => 'task'], function() {
        Route::get('list.html', 'TasksController@index')->name('page.task.list');
        Route::get('{task}/show.html', 'TasksController@show')->name('page.task.show');
        Route::post('store.html', 'TasksController@store')->name('page.task.store');
        Route::post('{task}/edit.html', 'TasksController@edit')->name('page.task.edit');
        Route::post('task.html', 'TasksController@search_task')->name('page.task.search');
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('create.html', 'UsersController@create')->name('page.user.create');
        Route::get('show.html', 'UsersController@create')->name('page.user.show');
        Route::post('store.html', 'UsersController@store')->name('page.user.store');
    });
});