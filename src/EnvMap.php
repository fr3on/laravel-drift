<?php

namespace Fr3on\Drift;

class EnvMap
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function missing(string $key): bool
    {
        return ! $this->has($key);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function keys(): array
    {
        return array_keys($this->data);
    }
}
