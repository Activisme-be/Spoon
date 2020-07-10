<?php

namespace Tests;

use JMac\Testing\Traits\AdditionalAssertions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class TestCase
 *
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use AdditionalAssertions;
}
