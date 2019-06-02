<?php

namespace Tests\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Trait DummyNotificationsTrait
 *
 * @package Tests\Concerns
 */
trait DummyNotificationsTrait
{
    /**
     * Method for generating fake notifications.
     *
     * @throws \Exception <- Native PHP class
     *
     * @param  int|null $amount The amount of fake notifications u want to create.
     * @return DatabaseNotification|Collection
     */
    private function createDummyNotification(?int $amount = null)
    {
        $data = [
            'id' => faker()->uuid,
            'type' => 'Namespace\ClassNameOfNotification',
            'notifiable_type' => "Notifiable\Model",
            'notifiable_id' => random_int(8888, 9999), // id of the notifiable model
            'data' => [
                'any' => 'value'
            ],
        ];

        if ($amount === null) { // There is not given an amount of dummy notifications
            return factory(DatabaseNotification::class)->create($data);
        }

        return factory(DatabaseNotification::class, $amount)->create($data);
    }
}
