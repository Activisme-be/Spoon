<?php 

namespace App\Traits; 

/**
 * Trait activityLog logging all the internal handlings from users in the application. 
 * 
 * @package App\Traits
 */
trait ActivityLog 
{
    /**
     * Log an activity message when an database entity has been changed. 
     * 
     * @param mixed  $model     The database model where the activity happend on. 
     * @param string $logName   The log message category 
     * @param string $message   The message that needs to be logged 
     */
    public function logActivity($model, string $logName = 'onbekend', string $message): void 
    {
        $user = auth()->user(); 
        activity($logName)->performedOn($model)->causedBy($user)->log($message);
    }
}