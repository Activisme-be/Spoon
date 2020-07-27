<?php

use App\Domain\Activity\Http\Controllers\ActivityController;
use App\Domain\Announcements\Http\Controllers\AnnouncementController;
use App\Http\Controllers\HomeController;
use App\Domain\Auth\Http\Controllers\ImpersonateController;
use App\Domain\Auth\Http\Controllers\UserController;
use App\Domain\Auth\Http\Controllers\LockController;
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

Route::get('/kiosk', [HomeController::class, 'kiosk'])->name('kiosk.dashboard');

// User routes
Route::match(['get', 'delete'], '/verwijder/gebruiker/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/gebruikers/zoek', [UserController::class, 'search'])->name('users.search');
Route::get('/gebruiker/{user}', [UserController::class, 'show'])->name('users.show');
Route::patch('/gebruikers/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/gebruikers/nieuw', [UserController::class, 'create'])->name('users.create');
Route::post('/gebruikers/nieuw', [UserController::class, 'store'])->name('users.store');
Route::get('/gebruikers/{filter?}', [UserController::class, 'index'])->name('users.index');

// User Impersonation routes
Route::get('/gebruiker/{user}/aanmelden/{guardName?}', [ImpersonateController::class, 'take'])->name('users.impersonate');
Route::get('/gebruiker/{user}/afmelden', [ImpersonateController::class, 'leave'])->name('users.impersonate.leave');

// User state routes
Route::get('/account/gedeactiveerd', [LockController::class, 'index'])->name('user.blocked');
Route::get('/{userEntity}/deactiveer', [LockController::class, 'create'])->name('users.lock');
Route::get('/{userEntity}/activeer', [LockController::class, 'destroy'])->name('users.unlock');
Route::post('/{userEntity}/deactiveer', [LockController::class, 'store'])->name('users.lock.store');

// System alert routes
Route::get('/alerts', [AnnouncementController::class, 'create'])->name('alerts.index');
Route::get('/alerts/overzicht', [AnnouncementController::class, 'index'])->name('alerts.overview');
Route::get('/alerts/{notification}', [AnnouncementController::class, 'show'])->name('alerts.show');
Route::post('/alerts', [AnnouncementController::class, 'store'])->name('alerts.store');

// Audit routes
Route::get('/audit', [ActivityController::class, 'index'])->name('audit.overview');
Route::get('/audit/zoeken', [ActivityController::class, 'search'])->name('audit.search');
Route::get('/audit/export/{filter?}', [ActivityController::class, 'export'])->name('audit.export');
Route::get('{user}/logs', [ActivityController::class, 'show'])->name('users.activity');
