<?php

namespace Fr3on\Drift\Rules;

use Fr3on\Drift\Contracts\DriftRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

class AppKeyRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        if ($env->missing('APP_KEY')) {
            return RuleResult::fail(
                'APP_KEY is missing.',
                'APP_KEY',
                'Run "php artisan key:generate" to create a new application key.'
            );
        }

        $key = $env->get('APP_KEY');

        // Check for placeholder
        if (str_contains($key, 'SomeRandomString')) {
            return RuleResult::fail(
                'APP_KEY is using a placeholder value.',
                'APP_KEY',
                'Generate a real key using "php artisan key:generate".'
            );
        }

        // Check length (typically base64:...)
        if (strlen($key) < 32) {
            return RuleResult::warn(
                'APP_KEY seems too short.',
                'APP_KEY',
                'Ensure your APP_KEY is a valid 32-character string (or base64 encoded).'
            );
        }

        return RuleResult::pass('APP_KEY is present and valid.', 'APP_KEY');
    }
}
