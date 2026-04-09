<?php

namespace Drift\Rules;

use Drift\Contracts\DriftRule;
use Drift\EnvMap;
use Drift\RuleResult;

class AppDebugRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        $debug = $env->get('APP_DEBUG');
        $envName = $env->get('APP_ENV', 'production');

        if ($envName === 'production' && ($debug === 'true' || $debug === true)) {
            return RuleResult::fail(
                'APP_DEBUG is enabled in production environment.',
                'APP_DEBUG',
                'Set APP_DEBUG=false in your production .env file to prevent sensitive data exposure.'
            );
        }

        return RuleResult::pass('APP_DEBUG is correctly configured.', 'APP_DEBUG');
    }
}
