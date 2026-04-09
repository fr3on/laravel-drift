<?php

namespace Fr3on\Drift\Tests;

use Fr3on\Drift\EnvMap;
use Fr3on\Drift\EnvParser;

test('it can parse a simple .env file', function () {
    $path = __DIR__.'/.env.test';
    file_put_contents($path, "KEY=VALUE\n# Comment\nFOO=\"BAR\"\n");

    $parser = new EnvParser;
    $env = $parser->parse($path);

    expect($env)->toBeInstanceOf(EnvMap::class);
    expect($env->get('KEY'))->toBe('VALUE');
    expect($env->get('FOO'))->toBe('BAR');

    unlink($path);
});

test('it returns empty map for non-existent file', function () {
    $parser = new EnvParser;
    $env = $parser->parse(__DIR__.'/missing.env');

    expect($env->all())->toBeEmpty();
});
