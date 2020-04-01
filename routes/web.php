<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\PasswordSecurityController;
use App\Http\Controllers\Auth\TwoFactorResetController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Users\AccountController;
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
/** @todo PHPUNIT */ Route::get('/home', 'HomeController@index')->name('home');

// Activity routes
/** @todo PHPUNIT */ Route::get('{user}/logs', [ActivityController::class, 'show'])->name('users.activity');

// Notification routes
/** @todo PHPUNIT */ Route::get('/notificaties/markAll', [NotificationController::class, 'markAll'])->name('notifications.markAll');
/** @todo PHPUNIT */ Route::get('/notificaties/markOne/{notification}', [NotificationController::class, 'markOne'])->name('notifications.markAsRead');
/** @todo PHPUNIT */ Route::get('/notificaties/{type?}', [NotificationController::class, 'index'])->name('notifications.index');

// User Settings routes
/** @todo PHPUNIT */ Route::get('/account', [AccountController::class, 'index'])->name('account.settings');
/** @todo PHPUNIT */ Route::get('/account/beveiliging', [AccountController::class, 'indexSecurity'])->name('account.security');
/** @todo PHPUNIT */ Route::patch('/account/informatie', [AccountController::class, 'updateInformation'])->name('account.settings.info');
/** @todo PHPUNIT */ Route::patch('/account/beveiliging', [AccountController::class, 'updateSecurity'])->name('account.settings.security');

// 2FA routes
/** @todo PHPUNIT */ Route::post('/gebruiker/genereer-2fa-token', [PasswordSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
/** @todo PHPUNIT */ Route::post('/gebruiker/2fa', [PasswordSecurityController::class, 'enable2fa'])->name('enable2fa');
/** @todo PHPUNIT */ Route::post('/gebruiker/deactiveer-2fa', [PasswordSecurityController::class, 'disable2fa'])->name('disable2fa');
/** @todo PHPUNIT */ Route::get('/2fa-herstel', [TwoFactorResetController::class, 'index'])->name('recovery.2fa');
/** @todo PHPUNIT */ Route::post('/2fa-herstel', [TwoFactorResetController::class, 'request'])->name('recovery.2fa.request');
/** @todo PHPUNIT */ Route::get('/2fa-reset', [TwoFactorResetController::class, 'handle'])->name('2fa.reset');

/** @todo PHPUNIT */ Route::post('/2faVerify', static function () {
    return redirect()->route('home');
})->name('2faVerify')->middleware('2fa');
