<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Drift Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure how Drift validates your environment.
    |
    */

    'env_file' => base_path('.env'),

    'example_file' => base_path('.env.example'),

    /*
    | Treat warnings as errors. When enabled, a warning will result in a
    | non-zero exit code, failing your CI/CD pipeline.
    */
    'strict' => env('DRIFT_STRICT', false),

    /*
    | Keys to ignore during validation.
    */
    'ignore' => [
        'VITE_*',
        'DEBUGBAR_*',
    ],

    /*
    | Register your custom rules here.
    */
    'rules' => [
        \Fr3on\Drift\Rules\AppDebugRule::class,
        \Fr3on\Drift\Rules\AppKeyRule::class,
        \Fr3on\Drift\Rules\CompletenessRule::class,
        \Fr3on\Drift\Rules\QueueDriverRule::class,
    ],
];
