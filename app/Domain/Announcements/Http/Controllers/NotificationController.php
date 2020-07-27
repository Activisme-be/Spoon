<?php

namespace App\Domain\Announcements\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\NotificationsRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

/**
 * NotificationController.
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    protected NotificationsRepository $notificationsRepository;

    public function __construct(NotificationsRepository $notificationsRepository)
    {
        $this->middleware(['auth', '2fa', 'forbid-banned-user']);
        $this->notificationsRepository = $notificationsRepository;
    }

    public function index(?string $type = null): Renderable
    {
        $notificationData = $this->notificationsRepository->getByType($type);
        $notificationsCount = ['unreadCount' => $this->getAuthenticatedUser()->unreadNotifications()->count()];
        $viewVariables = ['notifications' => $notificationData['notifications'], 'type' => $notificationData['type']];

        return view('notifications.index', array_merge($notificationsCount, $viewVariables));
    }

    public function markOne(DatabaseNotification $notification): RedirectResponse
    {
        $notification->markAsRead();
        return redirect()->route('notifications.index');
    }

    public function markAll(): RedirectResponse
    {
        $this->notificationsRepository->markAllAsRead();
        return redirect()->route('notifications.index');
    }
}
