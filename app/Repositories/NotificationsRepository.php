<?php

namespace App\Repositories;

use App\Models\SystemAlert;
use App\Models\User;
use App\Notifications\SystemAlert as AlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class NotificationsRepository
 *
 * @package App\Repositories
 */
class NotificationsRepository
{
    /**
     * Method for getting the entity from the authenticated user.
     *
     * @return User
     */
    protected function getAuthUser(): User
    {
        return auth()->user();
    }

    /**
     * Method for getting the notifications and determinating the request type.
     *
     * @param  string|null $type The type of notifications u want to display in the application.
     * @return array
     */
    public function getByType(?string $type = null): array
    {
        switch ($type) {
            case 'alle': return ['type' => 'alle', 'notifications' => $this->getAuthUser()->notifications()->simplePaginate()];
            default:     return ['type' => 'ongelezen', 'notifications' => $this->getAuthUser()->unreadNotifications()->simplePaginate()];
        }
    }

    /**
     * Method for marking all the unread notifications from the user as read.
     *
     * @return void
     */
    public function markAllAsRead(): void
    {
        $this->getAuthUser()->unreadNotifications->markAsread();
    }

    /**
     * Method sending out the system alert notification.
     *
     * @param  Request $input The request instance that holds all the request information.
     * @return bool
     */
    public function sendSystemAlert(Request $input): bool
    {
        $input->merge(['creator_id' => $this->getAuthUser()->id]);

        return DB::transaction(static function () use ($input): bool {
            $alert = SystemAlert::create($input->all());
            (new self)->sendOutNotifications($alert);

            return true;
        });
    }

    /**
     * Method to push the system alert notification to the queue.
     *
     * @param  SystemAlert $alertEntity THe entity from the notification data.
     * @return void
     */
    private function sendOutNotifications(SystemAlert $alertEntity): void
    {
        foreach (User::all() as $user) {
            $user->notify(new AlertNotification($alertEntity, $this->getAuthUser()));
        }
    }
}
