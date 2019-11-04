<?php

namespace App\Http\Controllers;

use App\Models\SystemAlert;
use Illuminate\Contracts\Support\Renderable;
use Spatie\Activitylog\Models\Activity;

/**
 * Class HomeController
 *
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
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'portal:application'])->only(['index']);
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'portal:kiosk'])->only(['kiosk']);
        $this->middleware(['guest'])->only(['welcome']);
    }

    /**
     * Get the first page of the application.
     *
     * @return Renderable
     */
    public function welcome(): Renderable
    {
        return view('welcome');
    }

    /**
     * Method for the dashboard page from the kiosk management.
     *
     * @return Renderable
     */
    public function kiosk(): Renderable
    {
        $logs = Activity::latest()->take(7)->get();
        $alerts = SystemAlert::latest()->take(7)->get();

        return view('kiosk', compact('logs', 'alerts'));
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
