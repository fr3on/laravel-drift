<?php

namespace Drift;

use Drift\Contracts\DriftRule;
use Illuminate\Support\Collection;

class RuleEngine
{
    /** @var array<class-string<DriftRule>> */
    private array $rules;

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * @return Collection<int, RuleResult>
     */
    public function execute(EnvMap $env, EnvMap $example): Collection
    {
        return collect($this->rules)
            ->map(function (string $ruleClass) use ($env, $example) {
                /** @var DriftRule $rule */
                $rule = new $ruleClass();
                return $rule->check($env, $example);
            })
            ->filter();
    }
}
