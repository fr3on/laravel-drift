<?php

namespace Fr3on\Drift;

use Fr3on\Drift\Commands\DriftCheckCommand;
use Illuminate\Support\ServiceProvider;

class DriftServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/drift.php', 'drift');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/drift.php' => config_path('drift.php'),
            ], 'drift-config');

            $this->commands([
                DriftCheckCommand::class,
            ]);
        }
    }
}
