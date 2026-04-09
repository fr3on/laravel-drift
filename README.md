# Laravel Drift

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fr3on/laravel-drift.svg?style=flat-square)](https://packagist.org/packages/fr3on/laravel-drift)
[![PHP Version](https://img.shields.io/badge/php-^8.2-777bb4.svg?style=flat-square&logo=php)](https://packagist.org/packages/fr3on/laravel-drift)
[![Laravel Version](https://img.shields.io/badge/laravel-^10.0%20%7C%20^11.0%20%7C%20^12.0-ff2d20.svg?style=flat-square&logo=laravel)](https://packagist.org/packages/fr3on/laravel-drift)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/fr3on/laravel-drift/ci.yml?branch=main&label=tests&style=flat-square)](https://github.com/fr3on/laravel-drift/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/fr3on/laravel-drift.svg?style=flat-square)](https://packagist.org/packages/fr3on/laravel-drift)

**Laravel Drift** is a pre-deploy environment validation and configuration drift detection tool. It acts as a gatekeeper, comparing your `.env` against `.env.example` and running safety checks *before* your application boots.

## Key Features

- **No-Boot Validation**: Runs without booting the Laravel application container for maximum safety and speed.
- **Drift Detection**: Identifies missing keys in `.env` or orphan keys in `.env.example`.
- **Safety Gates**: Prevents common deployment disasters like `APP_DEBUG=true` in production or using placeholder `APP_KEY` values.
- **Extensible Rules**: Easily add your own custom validation rules.
- **CI/CD Integration**: Simple exit code contracts to block failing builds.

## Installation

You can install the package via composer:

```bash
composer require fr3on/laravel-drift
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="drift-config"
```

## Usage

Run the drift check:

```bash
php artisan drift:check
```

In your CI/CD pipeline, use the `--strict` flag to treat warnings as errors:

```bash
php artisan drift:check --strict
```

## Built-in Rules

| Rule | Description | Status |
| :--- | :--- | :--- |
| `AppDebugRule` | Ensures `APP_DEBUG` is false when `APP_ENV` is production. | Fail |
| `AppKeyRule` | Validates presence, length, and ensures no placeholder values are used. | Fail |
| `CompletenessRule` | Compares `.env` keys against `.env.example`. | Fail/Warn |
| `QueueDriverRule` | Warns if `QUEUE_CONNECTION=sync` is used in production. | Warn |

## Custom Rules

You can create custom rules by implementing the `Fr3on\Drift\Contracts\DriftRule` interface:

```php
use Fr3on\Drift\Contracts\DriftRule;
use Fr3on\Drift\EnvMap;
use Fr3on\Drift\RuleResult;

class MyCustomRule implements DriftRule
{
    public function check(EnvMap $env, EnvMap $example): RuleResult
    {
        if ($env->missing('MY_REQUIRED_KEY')) {
            return RuleResult::fail('MY_REQUIRED_KEY is missing!');
        }

        return RuleResult::pass();
    }
}
```

Register your rules in `config/drift.php`.

## Credits

- [Ahmed Mardi](https://github.com/fr3on)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
