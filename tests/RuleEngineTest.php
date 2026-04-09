<?php

namespace Fr3on\Drift\Tests;

use Fr3on\Drift\RuleEngine;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;
use Fr3on\Drift\Contracts\DriftRule;
use Illuminate\Support\Collection;

class MockPassRule implements DriftRule {
    public function check(EnvMap $env, EnvMap $example): RuleResult {
        return RuleResult::pass('OK');
    }
}

class MockFailRule implements DriftRule {
    public function check(EnvMap $env, EnvMap $example): RuleResult {
        return RuleResult::fail('Bad');
    }
}

test('it can execute a list of rules', function () {
    $rules = [
        MockPassRule::class,
        MockFailRule::class,
    ];

    $engine = new RuleEngine($rules);
    $env = new EnvMap([]);
    $example = new EnvMap([]);

    $results = $engine->execute($env, $example);

    expect($results)->toBeInstanceOf(Collection::class);
    expect($results)->toHaveCount(2);
    expect($results[0]->isPass())->toBeTrue();
    expect($results[1]->isFail())->toBeTrue();
});
