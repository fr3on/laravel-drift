<?php

namespace Fr3on\Drift\Contracts;

use Illuminate\Support\Collection;

interface DriftReporter
{
    /**
     * Report the drift check results.
     *
     * @param  Collection<int, \Fr3on\Drift\RuleResult>  $results
     * @return void
     */
    public function report(Collection $results): void;
}
