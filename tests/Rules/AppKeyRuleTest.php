<?php

namespace Fr3on\Drift\Tests\Rules;

use Fr3on\Drift\Rules\AppKeyRule;
use Fr3on\Drift\EnvMap;

test('it fails when APP_KEY is missing', function () {
    $rule = new AppKeyRule();
    $env = new EnvMap([]);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isFail())->toBeTrue();
    expect($result->message)->toContain('missing');
});

test('it fails when APP_KEY is a placeholder', function () {
    $rule = new AppKeyRule();
    $env = new EnvMap(['APP_KEY' => 'SomeRandomString']);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isFail())->toBeTrue();
    expect($result->message)->toContain('placeholder');
});

test('it warns when APP_KEY is too short', function () {
    $rule = new AppKeyRule();
    $env = new EnvMap(['APP_KEY' => 'too-short']);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isWarn())->toBeTrue();
    expect($result->message)->toContain('too short');
});

test('it passes for a valid APP_KEY', function () {
    $rule = new AppKeyRule();
    $env = new EnvMap(['APP_KEY' => 'base64:u697vY8Z8R6S5Q4W3E2R1T0Y9U8I7O6P5L4K3J2H1G0=']);
    
    $result = $rule->check($env, new EnvMap([]));

    expect($result->isPass())->toBeTrue();
});
