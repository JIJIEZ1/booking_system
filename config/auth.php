<?php

return [

    'defaults' => [
    'guard' => 'customers',
    'passwords' => 'customers',
],


    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],

        // Custom guards
        'customers' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Custom providers
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],

        'staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],

        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Administrator::class,
        ],
    ],

    'passwords' => [

        // DEFAULT (Laravel User)
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'customers' => [
    'provider' => 'customers',
    'table' => 'password_resets', // <- use default table name
    'expire' => 60,
    'throttle' => 60,
],


    ],

    'password_timeout' => 10800,

];
