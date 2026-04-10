<?php

use Fr3on\Drift\Rules\OrphanKeyRule;
use Fr3on\Drift\Rules\PlaceholderValueRule;
use Fr3on\Drift\Rules\SessionDriverRule;

it('detects orphan keys in .env', function () {
    $rule = new OrphanKeyRule();
    
    // Key exists in env but not example
    $env = ['DB_PASSWORD' => 'secret', 'STRIPE_KEY' => 'pk_test'];
    $example = ['DB_PASSWORD' => ''];
    
    $result = $rule->check($env, $example);
    
    expect($result->passed)->toBeFalse();
    expect($result->message)->toContain('STRIPE_KEY');
});

it('detects placeholder values in .env', function () {
    $rule = new PlaceholderValueRule();
    
    $env = ['APP_NAME' => 'Laravel', 'API_KEY' => 'changeme'];
    $example = ['APP_NAME' => '', 'API_KEY' => ''];
    
    $result = $rule->check($env, $example);
    
    expect($result->passed)->toBeFalse();
    expect($result->message)->toContain('API_KEY');
});

it('warns about file session driver in production', function () {
    $rule = new SessionDriverRule();
    
    $env = ['APP_ENV' => 'production', 'SESSION_DRIVER' => 'file'];
    $example = ['APP_ENV' => '', 'SESSION_DRIVER' => ''];
    
    $result = $rule->check($env, $example);
    
    expect($result->passed)->toBeFalse();
    expect($result->severity)->toBe('warn');
});
