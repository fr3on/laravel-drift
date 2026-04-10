<?php

namespace Fr3on\Drift;

use Fr3on\Drift\Contracts\DriftRule;
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
                $rule = new $ruleClass;

                $result = $rule->check($env, $example);

                if ($result && is_null($result->rule)) {
                    $name = str_replace('Rule', '', class_basename($ruleClass));
                    $result->rule = strtolower($name);
                }

                return $result;
            })
            ->filter();
    }
}
