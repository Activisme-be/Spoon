<?php

namespace Tests\Feature\Back\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class MarkOneMethodTest
 *
 * @package Tests\Feature\Back\Notifications
 */
class MarkOneMethodTest extends TestCase
{
    use RefreshDatabase;

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

    /**
     * @test
     * @testdox Test if an unauthenticated user cant mark a notification as read.
     *
     * @throws \Exception <- Native PHP class
     */
    public function notAuthenticated(): void
    {
        $notification = $this->createDummyNotification();

        $route = route('notifications.markAsRead', $notification);

        $this->get($route)->assertRedirect(route('login'))->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'read_at' => null]);
    }

    /**
     * @test
     * @testdox Test that an deactivated user can't mark an notification as read.
     *
     * @throws \Exception <- Native PHP class
     */
    public function deactivatedUser(): void
    {
        $notification = $this->createDummyNotification();

        $route = route('notifications.markAsRead', $notification);
        $user = $this->createUserBlocked();

        $this->actingAs($user)->get($route)->assertRedirect(route('user.blocked'))->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'read_at' => null]);
    }

    /**
     * @test
     * @testdox Test that an authenticated user can mark a notification as read.
     *
     * @throws \Exception <- Native PHP class
     */
    public function authenticatedUserSuccess(): void
    {
        $notification = $this->createDummyNotification();

        $route = route('notifications.markAsRead', $notification);
        $user = $this->createUser('user');

        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'read_at' => null]);

        $this->actingAs($user)->get($route)->assertRedirect(route('notifications.index'))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id, 'read_at' => null]);
    }
}
