<?php

namespace Fr3on\Drift;

class EnvWriter
{
    /**
     * Create a backup of the .env file.
     */
    public function backup(string $path): bool
    {
        if (! file_exists($path)) {
            return false;
        }

        return copy($path, $path.'.bak');
    }

    /**
     * Append missing keys to the end of the .env file.
     */
    public function appendMissing(string $path, array $missingKeys): int
    {
        if (empty($missingKeys)) {
            return 0;
        }

        $content = "\n# --- Added by Laravel Drift on ".date('Y-m-d H:i:s')." ---\n";

        foreach ($missingKeys as $key => $value) {
            // If value contains spaces, wrap in quotes
            if (str_contains($value, ' ')) {
                $value = '"'.$value.'"';
            }

            $content .= "{$key}={$value}\n";
        }

        return file_put_contents($path, $content, FILE_APPEND);
    }
}
