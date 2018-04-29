<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

/*    'facebook' => [
        'client_id'     => '1340946242586565',
        'client_secret' => '87d7e5964709a5c17bced55f4a35c10c',
        //'redirect'      => url('login/facebook-callback'),
    ],
    'google' => [
        'client_id'     => '276026030244-rvpcmirntndv6d2rnafcu3eiq7mppaul.apps.googleusercontent.com',
        'client_secret' => 'BZ4o_m_PD_Iqt4azvEf5DZEY',
        //'redirect'      => url('login/google-callback'),
    ],*/
];
