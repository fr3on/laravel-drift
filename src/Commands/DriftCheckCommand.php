<?php

namespace Drift\Commands;

use Drift\EnvParser;
use Drift\RuleEngine;
use Drift\RuleResult;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DriftCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drift:check {--strict : Treat warnings as failures}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the environment for configuration drift and safety issues';

    /**
     * Execute the console command.
     */
    public function handle(EnvParser $parser): int
    {
        $this->newLine();
        $this->components->info('Laravel Drift v1.0 | Checking environment against .env.example');

        $envPath = config('drift.env_file', base_path('.env'));
        $examplePath = config('drift.example_file', base_path('.env.example'));

        $env = $parser->parse($envPath);
        $example = $parser->parse($examplePath);

        $engine = new RuleEngine(config('drift.rules', []));
        $results = $engine->execute($env, $example);

        $this->renderResults($results);

        $failures = $results->filter->isFail();
        $warnings = $results->filter->isWarn();
        $isStrict = $this->option('strict') || config('drift.strict', false);

        if ($failures->count() > 0 || ($isStrict && $warnings->count() > 0)) {
            $this->components->error(sprintf(
                'FAIL: %d error(s), %d warning(s) found.',
                $failures->count(),
                $warnings->count()
            ));

            return 1;
        }

        $this->components->info('Environment check passed!');
        return 0;
    }

    /**
     * @param Collection<int, RuleResult> $results
     */
    private function renderResults(Collection $results): void
    {
        foreach ($results as $result) {
            $status = $this->getStatusLabel($result);
            $key = $result->key ? "<fg=gray>{$result->key}</> " : '';
            
            $this->line("  {$status}  {$key}{$result->message}");
            
            if ($result->remediation && $this->output->isVerbose()) {
                $this->line("      <fg=gray>└─ {$result->remediation}</>");
            }
        }

        $this->newLine();
    }

    private function getStatusLabel(RuleResult $result): string
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
