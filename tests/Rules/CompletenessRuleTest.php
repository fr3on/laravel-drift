<?php

use Fr3on\Drift\Rules\CompletenessRule;
use Fr3on\Drift\EnvMap;

test('it fails when keys from .env.example are missing in .env', function () {
    $rule = new CompletenessRule();
    $env = new EnvMap(['APP_NAME' => 'Laravel']);
    $example = new EnvMap([
        'APP_NAME' => 'Laravel',
        'DB_CONNECTION' => 'mysql',
    ]);
    
    $result = $rule->check($env, $example);

    expect($result->isFail())->toBeTrue();
    expect($result->message)->toContain('missing 1 keys');
});

test('it warns when orphan keys exist in .env', function () {
    $rule = new CompletenessRule();
    $env = new EnvMap([
        'APP_NAME' => 'Laravel',
        'NEW_SECRET' => 'abc',
    ]);
    $example = new EnvMap(['APP_NAME' => 'Laravel']);
    
    $result = $rule->check($env, $example);

    expect($result->isWarn())->toBeTrue();
    expect($result->message)->toContain('1 orphan keys');
});

test('it passes when .env matches .env.example', function () {
    $rule = new CompletenessRule();
    $env = new EnvMap(['APP_NAME' => 'Laravel']);
    $example = new EnvMap(['APP_NAME' => 'Laravel']);
    
    $result = $rule->check($env, $example);

    expect($result->isPass())->toBeTrue();
});
