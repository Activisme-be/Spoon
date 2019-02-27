<?php 

namespace App\Repositories; 

use App\User;

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
     * Method for getting the notifications and determinating the request type 
     * 
     * @param  null|string $type The type of notifications u want to display in the application.
     * @return array 
     */
    public function getByType(?string $type = null): array 
    {
        switch($type) {
            case 'alle': 
                return ['type' => 'alle', 'notifications' => $this->getAuthUser()->notifications()->simplePaginate()];
            default: 
                return ['type' => 'ongelezen', 'notifications' => $this->getAuthUser()->unreadNotifications()->simplePaginate()];
        } 
    }
}