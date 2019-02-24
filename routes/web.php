<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Users\IndexController;

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

Auth::routes(['register' => false]);
 
// Home routes
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/home', 'HomeController@index')->name('home');

// Activity routes 
Route::get('{user}/logs', [ActivityController::class, 'show'])->name('user.activity');

// User routes
Route::get('/gebruiker/{user}', [IndexController::class, 'show'])->name('users.show');
Route::patch('/gebruikers/{user}', [IndexController::class, 'update'])->name('users.update');
Route::get('/gebruikers/nieuw', [IndexController::class, 'create'])->name('users.create');
Route::post('/gebruikers/nieuw', [IndexController::class, 'store'])->name('users.store');
Route::get('/gebruikers/{filter?}', [IndexController::class, 'index'])->name('users.index');