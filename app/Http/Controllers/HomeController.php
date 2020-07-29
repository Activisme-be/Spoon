<?php

namespace App\Http\Controllers;

use App\Domain\Announcements\Models\SystemAlert;
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
    public function __construct()
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'portal:application'])->only(['index']);
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'portal:kiosk'])->only(['kiosk']);
        $this->middleware(['guest'])->only(['welcome']);
    }

    public function welcome(): Renderable
    {
        return view('welcome');
    }

    public function kiosk(): Renderable
    {
        $logs = Activity::latest()->take(7)->get();
        $alerts = SystemAlert::latest()->take(7)->get();

        return view('kiosk', compact('logs', 'alerts'));
    }

    public function index(): Renderable
    {
        return view('home');
    }
}
