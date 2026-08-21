<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'sms' => [

        'base_url' => env(
            'SMS_BASE_URL',
            'http://its.idealsms.in/pushsms.php'
        ),

        'username' => env('SMS_USERNAME'),

        'password' => env('SMS_API_PASSWORD'),

        'sender' => env('SMS_SENDER', 'IDLSMS'),

        'priority' => env('SMS_PRIORITY', 11),

        'e_id' => env('SMS_E_ID'),

        't_id' => env('SMS_T_ID'),

        'te_id' => env('SMS_TE_ID'),

    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env(
            'MAILGUN_ENDPOINT',
            'api.mailgun.org'
        ),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env(
            'AWS_DEFAULT_REGION',
            'us-east-1'
        ),
    ],

];