<?php

use Fr3on\Drift\Rules\AppDebugRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

test('it fails when APP_DEBUG is true in production', function () {
    $rule = new AppDebugRule();
    $env = new EnvMap([
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'true',
    ]);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isFail())->toBeTrue();
    expect($result->message)->toContain('enabled in production');
});

test('it passes when APP_DEBUG is false in production', function () {
    $rule = new AppDebugRule();
    $env = new EnvMap([
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
    ]);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isPass())->toBeTrue();
});

test('it passes when APP_DEBUG is true in local', function () {
    $rule = new AppDebugRule();
    $env = new EnvMap([
        'APP_ENV' => 'local',
        'APP_DEBUG' => 'true',
    ]);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isPass())->toBeTrue();
});
