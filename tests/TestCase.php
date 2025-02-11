<?php

namespace Tests;

use Cre8it\Vincrease\VincreaseServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            VincreaseServiceProvider::class,
        ];
    }
}
