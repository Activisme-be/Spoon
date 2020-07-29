<?php

namespace App\Domain\Announcements\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Announcements\Http\Requests\SystemNotificationRequest;
use App\Domain\Announcements\Models\SystemAlert;
use App\Domain\Announcements\Repositories\NotificationsRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Class KioskController
 *
 * @package App\Http\Controllers\Alerts
 */
class AnnouncementController extends Controller
{
    protected NotificationsRepository $notifications;

    public function __construct(NotificationsRepository $notifications)
    {
        $this->notifications = $notifications;
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'role:webmaster', 'portal:kiosk', 'role:webmaster']);
    }

    public function index(SystemAlert $systemAlerts): Renderable
    {
        return view('notifications.kiosk.overview', ['notifications' => $systemAlerts->latest()->simplePaginate()]);
    }

    public function create(): Renderable
    {
        $drivers = ['database' => 'Web notificatie', 'mail' => 'E-mail notificatie'];
        return view('notifications.kiosk.index', compact('drivers'));
    }

    public function show(SystemAlert $notification): Renderable
    {
        return view('notifications.kiosk.show', compact('notification'));
    }

    public function store(SystemNotificationRequest $input): RedirectResponse
    {
        if ($this->notifications->sendSystemAlert($input)) {
            $this->getAuthenticatedUser()->logActivity(SystemAlert::latest()->first(), 'Systeem notificaties', 'Heeft een systeem notificatie verzonden.');
            flash('De systeem notificatie is opgeslagen en zal ASAP worden verzonden.', 'success');
        }

        return redirect()->route('alerts.overview');
    }
}
