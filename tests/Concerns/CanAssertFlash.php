<?php

namespace Tests\Concerns;

/**
 * Trait CanAssertFlash
 *
 * @package Tests
 */
trait CanAssertFlash
{
    protected function assertFlash(string $level, string $message, bool $important = false, ?string $title = null, bool $overlay = false): void
    {
        $expectedNotification = [
            'title' => $title, 'message' => $message, 'level' => $level, 'important' => $important, 'overlay' => $overlay
        ];

        $flashNotifications = json_decode(json_encode(session('flash_notification')), true);

        if (! $flashNotifications) {
            $this->fail('Failed asserting that a flash message was sent.');
        }

        $this->assertContains($expectedNotification, $flashNotifications, "Failed asserting that the flash message {$message} is present.");
    }
}
