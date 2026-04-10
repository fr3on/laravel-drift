<?php

namespace Fr3on\Drift\Rules;

use Fr3on\Drift\Contracts\DriftRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

class PlaceholderValueRule implements DriftRule
{
    protected array $placeholders = [
        'changeme',
        'secret',
        'TODO',
        'null',
        'undefined',
    ];

    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        $flaggedKeys = [];

        foreach ($env->all() as $key => $value) {
            foreach ($this->placeholders as $placeholder) {
                if (strtolower($value) === strtolower($placeholder)) {
                    $flaggedKeys[] = $key;
                    break;
                }
            }
        }

        if (count($flaggedKeys) > 0) {
            return RuleResult::fail(
                'Placeholder values detected in .env file.',
                'PlaceholderValues',
                'The following keys have placeholder values: '.implode(', ', $flaggedKeys).'. Please set real values before deploying.',
                ['flagged_keys' => $flaggedKeys]
            );
        }

        return RuleResult::pass('No placeholder values detected.', 'PlaceholderValues');
    }
}
