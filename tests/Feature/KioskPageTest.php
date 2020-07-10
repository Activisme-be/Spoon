<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class KioskPageTest
 *
 * @package Tests\Feature
 */
class KioskPageTest extends TestCase
{
    use RefreshDatabase;

    public function testMiddlewareImplementations(): void
    {
        $this->assertActionUsesMiddleware(HomeController::class, 'kiosk', [
            'auth', '2fa', 'forbid-banned-user', 'portal:kiosk'
        ]);
    }

    public function testHttpRequestIsSuccessFull(): void
    {
        $lena = factory(User::class)->create();
        $this->actingAs($lena)->get(route('kiosk.dashboard'))->assertStatus(200);
    }
}
