<?php

use Fr3on\Drift\EnvMap;
use Fr3on\Drift\Rules\OrphanKeyRule;
use Fr3on\Drift\Rules\PlaceholderValueRule;
use Fr3on\Drift\Rules\SessionDriverRule;

it('detects orphan keys in .env', function () {
    $rule = new OrphanKeyRule;

    // Key exists in env but not example
    $env = new EnvMap(['DB_PASSWORD' => 'secret', 'STRIPE_KEY' => 'pk_test']);
    $example = new EnvMap(['DB_PASSWORD' => '']);

    $result = $rule->check($env, $example);

    expect($result->isPass())->toBeFalse();
    expect($result->remediation)->toContain('STRIPE_KEY');
});

it('detects placeholder values in .env', function () {
    $rule = new PlaceholderValueRule;

    $env = new EnvMap(['APP_NAME' => 'Laravel', 'API_KEY' => 'changeme']);
    $example = new EnvMap(['APP_NAME' => '', 'API_KEY' => '']);

    $result = $rule->check($env, $example);

    expect($result->isPass())->toBeFalse();
    expect($result->remediation)->toContain('API_KEY');
});

it('warns about file session driver in production', function () {
    $rule = new SessionDriverRule;

    $env = new EnvMap(['APP_ENV' => 'production', 'SESSION_DRIVER' => 'file']);
    $example = new EnvMap(['APP_ENV' => '', 'SESSION_DRIVER' => '']);

    $result = $rule->check($env, $example);

    expect($result->isWarn())->toBeTrue();
    expect($result->status)->toBe('warn');
});
