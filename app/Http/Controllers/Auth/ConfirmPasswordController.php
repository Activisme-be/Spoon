<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

/**
 * Class ConfirmPasswordController
 *
 * This controller is responsible for handling the password confirmations
 * and uses a simple trait to include this behaviour. You're free to
 * explore this trait and override any methods you wish to tweak.
 *
 * @package App\Http\Controllers\Auth
 */
class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
