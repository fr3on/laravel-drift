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
        \Drift\Rules\AppDebugRule::class,
        \Drift\Rules\AppKeyRule::class,
        \Drift\Rules\CompletenessRule::class,
        \Drift\Rules\QueueDriverRule::class,
    ],
];
