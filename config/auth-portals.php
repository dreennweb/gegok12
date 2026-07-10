<?php

return [
    'guards' => [
        'applicant' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'department_staff' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'superadmin' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
];
