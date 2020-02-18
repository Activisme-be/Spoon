<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

/**
 * Class ConfirmPasswordController
 *
 * This controller is responsible for handling password confirmations and
 * uses a simple trait to include the behavior. You're free to explore
 * this trait and override any functions that require customization.
 *
 * @package App\Http\Controllers\Auth
 */
class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    protected string $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth');
    }
}
