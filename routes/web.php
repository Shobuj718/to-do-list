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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function() {
    
    

});

Route::get('/tasks', 'TaskController@index');

    Route::get('/tasks/all-task', 'TaskController@allTask')->name('all.task');

    Route::post('/tasks', 'TaskController@store')->name('tasks.store');

    Route::post('/tasks/update', 'TaskController@update')->name('tasks.update');
    Route::post('/tasks/live-update', 'TaskController@liveupdate')->name('tasks.live');

    Route::post('/active-tasks', 'TaskController@activeTasks')->name('tasks.active');
    Route::post('/completed-task', 'TaskController@completedTask')->name('tasks.completed');
    Route::post('/clear-task', 'TaskController@clearTask')->name('tasks.clear');
    Route::post('/all-task', 'TaskController@allTasks')->name('tasks.all');
