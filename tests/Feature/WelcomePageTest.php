<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class WelcomePageTest
 *
 * @package Tests\Feature
 */
class WelcomePageTest extends TestCase
{
    public function testCanViewTheWelcomePage(): void
    {
        $this->get(route('welcome'))->assertStatus(200);
    }
}
