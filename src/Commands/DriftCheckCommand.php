<?php

namespace Fr3on\Drift\Commands;

use Fr3on\Drift\Contracts\DriftReporter;
use Fr3on\Drift\EnvParser;
use Fr3on\Drift\EnvWriter;
use Fr3on\Drift\Reporters\JsonReporter;
use Fr3on\Drift\Reporters\TerminalReporter;
use Fr3on\Drift\RuleEngine;
use Illuminate\Console\Command;

class DriftCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drift:check 
                            {--strict : Treat warnings as failures}
                            {--fix : Attempt to automatically repair missing keys}
                            {--format=terminal : The output format (terminal, json)}';

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
        $format = $this->option('format');
        $isFix = $this->option('fix');

        if ($format === 'terminal') {
            $this->newLine();
            $this->components->info('Laravel Drift v1.0 | Checking environment against .env.example');
        }

        $envPath = config('drift.env_file', base_path('.env'));
        $examplePath = config('drift.example_file', base_path('.env.example'));

        $env = $parser->parse($envPath);
        $example = $parser->parse($examplePath);

        $engine = new RuleEngine(config('drift.rules', []));
        $results = $engine->execute($env, $example);

        // 1. Handle Automatic Repair
        if ($isFix && file_exists($envPath)) {
            $missingKeys = [];
            foreach ($results as $result) {
                if ($result->rule === 'completeness' && isset($result->metadata['missing'])) {
                    foreach ($result->metadata['missing'] as $key) {
                        $missingKeys[$key] = $example->get($key, '');
                    }
                }
            }

            if (! empty($missingKeys)) {
                $writer = new EnvWriter;
                $writer->backup($envPath);
                $writer->appendMissing($envPath, $missingKeys);

                if ($format === 'terminal') {
                    $this->components->warn(sprintf('Repaired %d missing key(s). Backup created at .env.bak', count($missingKeys)));
                }

                // Refresh data for final report
                $results = $engine->execute($parser->parse($envPath), $example);
            }
        }

        $this->getReporter($format)->report($results);

        $failures = $results->filter->isFail();
        $warnings = $results->filter->isWarn();
        $isStrict = $this->option('strict') || config('drift.strict', false);

        if ($failures->count() > 0 || ($isStrict && $warnings->count() > 0)) {
            if ($format === 'terminal') {
                $this->components->error(sprintf(
                    'FAIL: %d error(s), %d warning(s) found.',
                    $failures->count(),
                    $warnings->count()
                ));
            }

            return 1;
        }

        if ($format === 'terminal') {
            $this->components->info('Environment check passed!');
        }

        return 0;
    }

    protected function getReporter(string $format): DriftReporter
    {
        return match ($format) {
            'json' => new JsonReporter($this->output),
            default => new TerminalReporter($this->output, $this->components),
        };
    }
}
