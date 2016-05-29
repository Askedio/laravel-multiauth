<?php

return [

    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    |
    |
    */

    'drivers' => [ // List of drivers to not be treated as Socialite.
        'link',
        'email',
    ],
    'redirectToOnError' => '/', // When the session redirect is lost, redirect here.

    'email' => [
        'register' => [
          'email'    => 'required|email|max:255',
          'password' => 'required',
        ],
        'login' => [
          'email'    => 'required|email|max:255',
          'password' => 'required',
        ],
        'confirm' => true,
        'expires' => '+15 minutes',
    ],

    'link' => [
        'register' => [
            'email_auth' => 'required|email|max:255',
        ],
        'login' => [
           'token' => 'required',
        ],
        'expires' => '+15 minutes',
        'field'  => 'email_auth',
    ],

    'socialite' => [
        'register' => [
          'email' => 'required|email|max:255',
        ],
        'login' => [
        ],
        'expires' => '+1 month',
    ],

];
