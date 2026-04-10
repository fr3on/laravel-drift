<?php

namespace Fr3on\Drift\Reporters;

use Fr3on\Drift\Contracts\DriftReporter;
use Fr3on\Drift\RuleResult;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\OutputInterface;

class TerminalReporter implements DriftReporter
{
    public function __construct(
        protected OutputInterface $output,
        protected Factory $components
    ) {}

    public function report(Collection $results): void
    {
        foreach ($results as $result) {
            $status = $this->getStatusLabel($result);
            $key = $result->key ? "<fg=gray>{$result->key}</> " : '';

            $this->output->writeln("  {$status}  {$key}{$result->message}");

            if ($result->remediation) {
                $this->output->writeln("      <fg=gray>└─ {$result->remediation}</>");
            }
        }
    }

    protected function getStatusLabel(RuleResult $result): string
    {
        return match ($result->status) {
            RuleResult::STATUS_PASS => '<fg=green>✓</>',
            RuleResult::STATUS_WARN => '<fg=yellow>~</>',
            RuleResult::STATUS_FAIL => '<fg=red>✗</>',
            RuleResult::STATUS_SKIP => '<fg=gray>○</>',
            default => '?',
        };
    }
}
