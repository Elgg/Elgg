<?php

return [
    'routes' => [
        'default:bin' => [
            'path' => '/bin',
            'resource' => 'bin',
        ],
    ],
    'events' => [
        'register' => [
            'menu:topbar' => [
                'Elgg\bin\Menus\Topbar::register' => [],
            ],
        ]
    ]
];