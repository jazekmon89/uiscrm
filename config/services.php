<?php
use App\Helpers\OrganisationHelper;
$org = OrganisationHelper::getCurrentOrganisationAbbrv();

$fb = [
    'client_id' => '177379226000549', // live
    'client_secret' => '575e7fe5d68fb9748238835973c93755', // live
    //'client_id' => '187734181631720',
    //'client_secret' => '1706bc353d53ec7a9c787990a1c60c43',
    'redirect' => '',
    //'redirect' => 'http://crm.ultrainsurance.com.au/fb/callback',
    //'redirect' => 'http://www.uis.local/fbcallback',
];
if($org == 'jci'){
    $fb = [
        'client_id' => '1774272722892627',
        'client_secret' => 'f5588a8c2b7daef2a37fdc70b7f51e90',
        'redirect' => '',
    ];
}

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
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
    'facebook' => $fb,
    'google' => [
        'client_id' => '463069936771-h2nhgk0k6inlpa7qljtofdr76jkdb7ar.apps.googleusercontent.com',
        'client_secret' => 'hToQfKkbBE2UIcIElW7fS0lx',
        'redirect' => '',
        //'redirect' => 'http://localhost:8000/google/callback',
    ],

];
