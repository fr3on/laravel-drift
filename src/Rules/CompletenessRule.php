<?php

namespace Fr3on\Drift\Rules;

use Fr3on\Drift\Contracts\DriftRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

class CompletenessRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        $missing = [];
        foreach ($example->keys() as $key) {
            if ($env->missing($key)) {
                $missing[] = $key;
            }
        }

        if (count($missing) > 0) {
            return RuleResult::fail(
                sprintf('Environment is missing %d keys defined in .env.example.', count($missing)),
                implode(', ', $missing),
                'Add the missing keys to your .env file.'
            );
        }

        $orphans = [];
        foreach ($env->keys() as $key) {
            if ($example->missing($key) && ! $this->isIgnored($key)) {
                $orphans[] = $key;
            }
        }

        if (count($orphans) > 0) {
            return RuleResult::warn(
                sprintf('Environment contains %d orphan keys not in .env.example.', count($orphans)),
                implode(', ', $orphans),
                'Consider documenting these keys in .env.example or removing them if unused.'
            );
        }

        return RuleResult::pass('Environment matches .env.example structure.');
    }

    private function isIgnored(string $key): bool
    {
        // Simple ignore list for common local/temp variables
        $ignored = ['DRIFT_STRICT', 'PORT'];
        return in_array($key, $ignored);
    }
}
