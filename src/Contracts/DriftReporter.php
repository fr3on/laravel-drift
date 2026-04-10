<?php

namespace Fr3on\Drift\Contracts;

use Fr3on\Drift\RuleResult;
use Illuminate\Support\Collection;

interface DriftReporter
{
    /**
     * Report the drift check results.
     *
     * @param  Collection<int, RuleResult>  $results
     */
    public function report(Collection $results): void;
}
