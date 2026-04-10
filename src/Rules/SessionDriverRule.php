<?php

namespace Fr3on\Drift\Rules;

use Fr3on\Drift\Contracts\DriftRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

class SessionDriverRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        $sessionDriver = $env->get('SESSION_DRIVER', 'file');
        $envName = $env->get('APP_ENV', 'production');

        if ($envName === 'production' && $sessionDriver === 'file') {
            return RuleResult::warn(
                'Insecure or inefficient session driver in production.',
                'SessionDriver',
                'SESSION_DRIVER is set to "file" in production. Consider using "redis" or "database" for better performance and scalability.'
            );
        }

        return RuleResult::pass('Session driver is acceptable.', 'SessionDriver');
    }
}
