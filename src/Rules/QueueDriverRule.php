<?php

namespace Drift\Rules;

use Drift\Contracts\DriftRule;
use Drift\EnvMap;
use Drift\RuleResult;

class QueueDriverRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        $driver = $env->get('QUEUE_CONNECTION');
        $envName = $env->get('APP_ENV', 'production');

        if ($envName === 'production' && $driver === 'sync') {
            return RuleResult::warn(
                'QUEUE_CONNECTION is set to "sync" in production.',
                'QUEUE_CONNECTION',
                'Background jobs will run synchronously, potentially slowing down requests. Consider using "redis" or "sqs".'
            );
        }

        return RuleResult::pass('Queue driver is appropriately configured.', 'QUEUE_CONNECTION');
    }
}
