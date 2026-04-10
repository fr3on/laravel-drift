<?php

use Fr3on\Drift\EnvParser;
use Fr3on\Drift\EnvWriter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('repairs missing keys in .env when --fix is used', function () {
    $tempEnv = tempnam(sys_get_temp_dir(), '.env');
    $tempExample = tempnam(sys_get_temp_dir(), '.env.example');

    File::put($tempEnv, "APP_NAME=Laravel\n");
    File::put($tempExample, "APP_NAME=\nSTRIPE_KEY=\n");

    config(['drift.env_file' => $tempEnv]);
    config(['drift.example_file' => $tempExample]);
    // Ensure completeness rule is active in config
    config(['drift.rules' => [\Fr3on\Drift\Rules\CompletenessRule::class]]);

    Artisan::call('drift:check', ['--fix' => true]);

    $content = File::get($tempEnv);
    expect($content)->toContain('STRIPE_KEY=');
    expect(File::exists($tempEnv . '.bak'))->toBeTrue();

    unlink($tempEnv);
    unlink($tempEnv . '.bak');
    unlink($tempExample);
});
