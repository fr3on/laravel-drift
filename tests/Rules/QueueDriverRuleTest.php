<?php

use Fr3on\Drift\Rules\QueueDriverRule;
use Fr3on\Drift\EnvMap;

test('it warns when queue driver is sync in production', function () {
    $rule = new QueueDriverRule();
    $env = new EnvMap([
        'APP_ENV' => 'production',
        'QUEUE_CONNECTION' => 'sync',
    ]);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isWarn())->toBeTrue();
    expect($result->message)->toContain('set to "sync" in production');
});

test('it passes when queue driver is redis in production', function () {
    $rule = new QueueDriverRule();
    $env = new EnvMap([
        'APP_ENV' => 'production',
        'QUEUE_CONNECTION' => 'redis',
    ]);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isPass())->toBeTrue();
});
