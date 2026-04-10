<?php

namespace Fr3on\Drift\Reporters;

use Fr3on\Drift\Contracts\DriftReporter;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\OutputInterface;

class JsonReporter implements DriftReporter
{
    public function __construct(
        protected OutputInterface $output
    ) {}

    public function report(Collection $results): void
    {
        $data = $results->map(fn ($result) => [
            'status' => $result->status,
            'key' => $result->key,
            'message' => $result->message,
            'remediation' => $result->remediation,
            'metadata' => $result->metadata,
        ]);

        $this->output->writeln(json_encode($data, JSON_PRETTY_PRINT));
    }
}
