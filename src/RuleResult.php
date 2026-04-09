<?php

namespace Fr3on\Drift;

class RuleResult
{
    public const STATUS_PASS = 'pass';

    public const STATUS_WARN = 'warn';

    public const STATUS_FAIL = 'fail';

    public const STATUS_SKIP = 'skip';

    public function __construct(
        public string $status,
        public string $message,
        public ?string $key = null,
        public ?string $remediation = null
    ) {}

    public static function pass(string $message = 'Condition met', ?string $key = null): self
    {
        return new self(self::STATUS_PASS, $message, $key);
    }

    public static function warn(string $message, ?string $key = null, ?string $remediation = null): self
    {
        return new self(self::STATUS_WARN, $message, $key, $remediation);
    }

    public static function fail(string $message, ?string $key = null, ?string $remediation = null): self
    {
        return new self(self::STATUS_FAIL, $message, $key, $remediation);
    }

    public static function skip(string $message, ?string $key = null): self
    {
        return new self(self::STATUS_SKIP, $message, $key);
    }

    public function isPass(): bool
    {
        return $this->status === self::STATUS_PASS;
    }

    public function isWarn(): bool
    {
        return $this->status === self::STATUS_WARN;
    }

    public function isFail(): bool
    {
        return $this->status === self::STATUS_FAIL;
    }
}
