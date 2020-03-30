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
    protected function getAuthUser(): User
    {
        return auth()->user();
    }

    public function getByType(?string $type = null): array
    {
        switch ($type) {
            case 'alle': return ['type' => 'alle', 'notifications' => $this->getAuthUser()->notifications()->simplePaginate()];
            default:     return ['type' => 'ongelezen', 'notifications' => $this->getAuthUser()->unreadNotifications()->simplePaginate()];
        }
    }

    public function markAllAsRead(): void
    {
        $this->getAuthUser()->unreadNotifications->markAsread();
    }

    public function sendSystemAlert(Request $input): bool
    {
        $input->merge(['creator_id' => $this->getAuthUser()->id]);

        return DB::transaction(function () use ($input): bool {
            $alert = SystemAlert::create($input->all());
            $this->sendOutNotifications($alert);

            return true;
        });
    }

    private function sendOutNotifications(SystemAlert $alertEntity): void
    {
        foreach (User::all() as $user) {
            $user->notify(new AlertNotification($alertEntity, $this->getAuthUser()));
        }
    }
}
