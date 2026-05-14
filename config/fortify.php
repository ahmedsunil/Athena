<?php

use Laravel\Fortify\Features;

return [
    'guard'      => 'web',
    'middleware' => ['web'],
    'prefix'     => '',
    'domain'     => null,
    'home'       => '/dashboard',

    'limiters' => [
        'login'      => 'login',
        'two-factor' => 'two-factor',
    ],

    'views'    => true,
    'username' => 'email',
    'email'    => 'email',
    'passwords'   => 'users',
    'subdomains'  => false,

    'features' => [
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm'         => true,
            'confirmPassword' => true,
        ]),
    ],
];
