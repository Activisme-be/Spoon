<?php

namespace Tests\Feature\Back\Notifications;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Concerns\DummyNotificationsTrait;
use Tests\TestCase;

/**
 * Class MarkAllMethodTest
 *
 * @package Tests\Feature\Back\Notifications
 */
class MarkAllMethodTest extends TestCase
{
    use RefreshDatabase, DummyNotificationsTrait;

    /**
     * @test
     * @testdox Test that an quest user can't mark all the notifications as read.
     *
     * @throws Exception <- Native PHP class
     */
    public function unauthenticated(): void
    {
        $firstNotification = $this->createDummyNotification();
        $secondNotification = $this->createDummyNotification();

        $this->get(route('notifications.markAll'))->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('notifications', ['id' => $firstNotification->id, 'read_at' => null]);
        $this->assertDatabaseHas('notifications', ['id' => $secondNotification->id, 'read_at' => null]);
    }

    /**
     * @test
     * @testdox Test That an deactivated user can't mark any notification as read.
     *
     * @throws Exception <- Native PHP class
     */
    public function deactivatedUser(): void
    {
        $firstNotification = $this->createDummyNotification();
        $secondNotification = $this->createDummyNotification();

        $user = $this->createUserBlocked();

        $this->actingAs($user)->get(route('notifications.markAll'))->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('user.blocked'));

        $this->assertDatabaseHas('notifications', ['id' => $firstNotification->id, 'read_at' => null]);
        $this->assertDatabaseHas('notifications', ['id' => $secondNotification->id, 'read_at' => null]);
    }

    /**
     * @test
     * @testdox Test that an authenticated can mark all his notifications as read.
     *
     * @throws Exception <- Native PHP class
     */
    public function success(): void
    {
        $firstNotification = $this->createDummyNotification();
        $secondNotification = $this->createDummyNotification();

        $user = $this->createUser('user');
        $this->actingAs($user)->get(route('notifications.markAll'))->assertStatus(Response::HTTP_OK);
    }
}
