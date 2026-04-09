<?php

namespace Fr3on\Drift;

class EnvParser
{
    /**
     * Parse the given .env file into an EnvMap.
     * We avoid using framework-specific loaders to keep this "pre-boot" compatible.
     */
    public function parse(string $path): EnvMap
    {
        if (! file_exists($path)) {
            return new EnvMap([]);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $data = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Handle Key=Value
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = $this->stripQuotes(trim($value));

                // Handle inline comments: KEY=VALUE # comment
                if (str_contains($value, '#')) {
                    $value = trim(explode('#', $value)[0]);
                }

                $data[$key] = $value;
            }
        }

        return new EnvMap($data);
    }

    private function stripQuotes(string $value): string
    {
        if (strlen($value) < 2) {
            return $value;
        }

        $first = $value[0];
        $last = $value[strlen($value) - 1];

        if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
