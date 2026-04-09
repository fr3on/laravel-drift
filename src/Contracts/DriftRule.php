<?php

namespace Drift\Contracts;

use Drift\EnvMap;
use Drift\RuleResult;

interface DriftRule
{
    /**
     * Check the environment map against the rule logic.
     */
    public function check(EnvMap $env, EnvMap $example): RuleResult;
}
