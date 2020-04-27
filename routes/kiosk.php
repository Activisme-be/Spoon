<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Alerts\KioskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Users\IndexController;
use App\Http\Controllers\Users\LockController;
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

/** @todo PHPUNIT */ Route::get('/kiosk', [HomeController::class, 'kiosk'])->name('kiosk.dashboard');

// User routes
/** @todo PHPUNIT */ Route::match(['get', 'delete'], '/verwijder/gebruiker/{user}', [IndexController::class, 'destroy'])->name('users.destroy');
/** @todo PHPUNIT */ Route::get('/gebruikers/zoek', [IndexController::class, 'search'])->name('users.search');
/** @todo PHPUNIT */ Route::get('/gebruiker/{user}', [IndexController::class, 'show'])->name('users.show');
/** @todo PHPUNIT */ Route::patch('/gebruikers/{user}', [IndexController::class, 'update'])->name('users.update');
/** @todo PHPUNIT */ Route::get('/gebruikers/nieuw', [IndexController::class, 'create'])->name('users.create');
/** @todo PHPUNIT */ Route::post('/gebruikers/nieuw', [IndexController::class, 'store'])->name('users.store');
/** @todo PHPUNIT */ Route::get('/gebruikers/{filter?}', [IndexController::class, 'index'])->name('users.index');

// User state routes
/** @todo PHPUNIT */ Route::get('/account/gedeactiveerd', [LockController::class, 'index'])->name('user.blocked');
/** @todo PHPUNIT */ Route::get('/{userEntity}/deactiveer', [LockController::class, 'create'])->name('users.lock');
/** @todo PHPUNIT */ Route::get('/{userEntity}/activeer', [LockController::class, 'destroy'])->name('users.unlock');
/** @todo PHPUNIT */ Route::post('/{userEntity}/deactiveer', [LockController::class, 'store'])->name('users.lock.store');

// System alert routes
/** @todo PHPUNIT */ Route::get('/alerts', [KioskController::class, 'create'])->name('alerts.index');
/** @todo PHPUNIT */ Route::get('/alerts/overzicht', [KioskController::class, 'index'])->name('alerts.overview');
/** @todo PHPUNIT */ Route::get('/alerts/{notification}', [KioskController::class, 'show'])->name('alerts.show');
/** @todo PHPUNIT */ Route::post('/alerts', [KioskController::class, 'store'])->name('alerts.store');

// Audit routes
/** @todo PHPUNIT */ Route::get('/audit', [ActivityController::class, 'index'])->name('audit.overview');
/** @todo PHPUNIT */ Route::get('/audit/zoeken', [ActivityController::class, 'search'])->name('audit.search');
/** @todo PHPUNIT */ Route::get('/audit/export/{filter?}', [ActivityController::class, 'export'])->name('audit.export');
