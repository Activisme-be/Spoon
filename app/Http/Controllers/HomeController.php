<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * ---- 
 * Controllers that handles the application home pages. 
 * 
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth'])->only(['index']);
        $this->middleware(['guest'])->only(['welcome']);
    }

    /**
     * Get the first page of the application. 
     * 
     * @return Renderable
     */
    public function welcome(): Renderable 
    {
        return view('auth.login');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('home');
    }
}
