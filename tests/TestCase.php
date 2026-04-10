<?php

namespace Fr3on\Drift\Tests;

use Fr3on\Drift\DriftServiceProvider;
use Fr3on\Drift\Rules\CompletenessRule;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            DriftServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Setup default config
        $app['config']->set('drift.rules', [
            CompletenessRule::class,
        ]);
    }
}
