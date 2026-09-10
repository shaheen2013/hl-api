<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'gateway' => [
        'url'    => env('GATEWAY_URL'),
        'stream' => env('STREAM_SUB_DOMAIN'),
    ],

    'hotelinking' => [
        'url' => env('HOTELINKING_URL')
    ],

    'surveys' => [
        'url' => env('SURVEYS_URL'),
        'avatar-placeholder' => env('SURVEYS_AVATAR_PLACEHOLDER', ''),
        'cacheLifeInSeconds' => 3600 * 24, // 1 day
        'paginate' => [
            'max' => 500,
            'default' => 100
        ],
    ],
    'users' => [
        'cacheLifeInSeconds' => 3600 * 24, // 1 day
        'paginate' => [
            'max' => 500,
            'default' => 100
        ],
    ],

    'eventbus' => [
        'name' => env('EVENT_BUS_NAME', 'dev-eventbus'),
    ],

    'bouncer' => [
        'url' => env('BOUNCER_URL'),
    ],
    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe'  => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'autochekin' => env('AUTOCHECKIN_REDIRECT_URL'),
    'aws' => [
        'key'    => env('AWS_KEY'),
        'secret' => env('AWS_SECRET'),
        'region' => env('AWS_REGION', 'eu-west-1'),
        'version' => env('AWS_VERSION', 'latest'),
        'cognito' => [
            'app_client_id'     => env('AWS_COGNITO_CLIENT_ID'),
            'app_client_secret' => env('AWS_COGNITO_CLIENT_SECRET', ''),
            'user_pool_id'      => env('AWS_COGNITO_USER_POOL_ID'),
            'username_field'    => 'username',
        ],
        'cognito-suite' => [
            'credentials' => [
                'key' => env('AWS_KEY'),
                'secret' => env('AWS_SECRET'),
            ],
            'region' => env('AWS_REGION', 'eu-west-1'),
            'version' => env('AWS_VERSION', 'latest'),
            'app_client_id' => env('AWS_COGNITO_SUITE_CLIENT_ID'),
            'app_client_secret' => env('AWS_COGNITO_SUITE_CLIENT_SECRET'),
            'user_pool_id' => env('AWS_COGNITO_SUITE_USER_POOL_ID'),
        ],
        'gateway' => [
            'id' => env('AWS_GATEWAY_ID'),
            'url' => env('GATEWAY_URL'),
            'stream' => env('AWS_GATEWAY_STREAM'),
            'hotelinking_api' => env('HOTELINKING_DOMAIN', '')
        ],
        'sns' => [
            'datamatch' => env("AWS_SNS_DATAMATCH_ARN")
        ],
        'schemas' => [
            'table' => env('SCHEMAS_TABLE')
        ],
        'event-bridge' => [
            'credentials' => [
                'key'     => env('AWS_KEY'),
                'secret'  => env('AWS_SECRET'),
            ],
            'region'  => env('AWS_REGION', 'eu-west-1'),
            'source' => env('EVENT_BUS_SOURCE_NAME', 'hl.api'),
            'version' => 'latest',
            'name' => env("AWS_EVENT_BUS_NAME", '')
        ]
    ],
    'mandrill' => [
        'key' => env('MANDRILL_KEY')
    ]
];
