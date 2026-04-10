<?php

namespace Fr3on\Drift\Rules;

use Fr3on\Drift\Contracts\DriftRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

class OrphanKeyRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        $orphanKeys = [];
        $envKeys = $env->keys();
        $exampleKeys = $example->keys();

        foreach ($envKeys as $key) {
            if (! in_array($key, $exampleKeys)) {
                $orphanKeys[] = $key;
            }
        }

        if (count($orphanKeys) > 0) {
            return RuleResult::warn(
                'Orphan keys detected in .env file.',
                'OrphanKeys',
                'The following keys exist in .env but are missing from .env.example: '.implode(', ', $orphanKeys),
                ['orphan_keys' => $orphanKeys]
            );
        }

        return RuleResult::pass('No orphan keys detected.', 'OrphanKeys');
    }
}
