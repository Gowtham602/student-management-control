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
    // sms config take to env file 

    'sms' => [
    'username' => env('SMS_USERNAME'),
    'password' => env('SMS_API_PASSWORD'),
    'sender'   => env('SMS_SENDER'),
    'priority' => env('SMS_PRIORITY'),
    'e_id'     => env('SMS_E_ID'),
    't_id'     => env('SMS_T_ID'),
    'base_url' => env('SMS_BASE_URL'),
],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    //msm message templeted t parent 
    'idealsms' => [

    'url' => env(
        'IDEAL_SMS_URL',
        'http://its.idealsms.in/pushsms.php'
    ),

    'username' => env('IDEAL_SMS_USERNAME'),

    'password' => env('IDEAL_SMS_PASSWORD'),

    'sender' => env('IDEAL_SMS_SENDER', 'IDLSMS'),

    'entity_id' => env('IDEAL_SMS_ENTITY_ID'),

    'templates' => [

        'parent_meeting' =>
            env('IDEAL_SMS_PARENT_MEETING_TEMPLATE_ID'),

        'parent_meeting_tamil' =>
            env('IDEAL_SMS_PARENT_MEETING_TAMIL_TEMPLATE_ID'),

    ],

],

];
