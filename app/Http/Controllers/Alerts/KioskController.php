<?php

namespace App\Http\Controllers\Alerts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alerts\SystemNotificationRequest;
use App\Models\SystemAlert;
use App\Repositories\NotificationsRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Class KioskController
 *
 * @package App\Http\Controllers\Alerts
 */
class KioskController extends Controller
{
    /**
     * Variable for the notification layer in the application.
     *
     * @var NotificationsRepository
     */
    protected $notifications;

    /**
     * KioskController constructor.
     *
     * @param  NotificationsRepository $notifications Implementation of the layer.
     * @return void
     */
    public function __construct(NotificationsRepository $notifications)
    {
        $this->notifications = $notifications;
        $this->middleware(['auth', '2fa', 'forbid-banned-user', 'role:webmaster', 'portal:kiosk', 'role:webmaster']);
    }

    /**
     * Method for displaying the overview of notifications that are sended.
     *
     * @param  SystemAlert $systemAlerts The database model instance form the notifications in the storage.
     * @return Renderable
     */
    public function index(SystemAlert $systemAlerts): Renderable
    {
        return view('notifications.kiosk.overview', ['notifications' => $systemAlerts->latest()->simplePaginate()]);
    }

    /**
     * Index method for the system alerts.
     *
     * As index view we have the create view for an new system wide alert message (notification).
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        $drivers = ['database' => 'Web notificatie', 'mail' => 'E-mail notificatie'];
        return view('notifications.kiosk.index', compact('drivers'));
    }

    /**
     * Method for displaying an system alert in the application.
     *
     * @param  SystemAlert $notification The notification entity from the database storage.
     * @return Renderable
     */
    public function show(SystemAlert $notification): Renderable
    {
        return view('notifications.kiosk.show', compact('notification'));
    }

    /**
     * Method for sending a system wide notification.
     *
     * @param  SystemNotificationRequest $input The form request class that handles the validation.
     * @return RedirectResponse
     */
    public function store(SystemNotificationRequest $input): RedirectResponse
    {
        if ($this->notifications->sendSystemAlert($input)) {
            $this->getAuthenticatedUser()->logActivity(SystemAlert::latest()->first(), 'Systeem notificaties', 'Heeft een systeem notificatie verzonden.');
            flash('De systeem notificatie is opgeslagen en zal ASAP worden verzonden.', 'success');
        }

        return redirect()->route('alerts.overview');
    }
}
