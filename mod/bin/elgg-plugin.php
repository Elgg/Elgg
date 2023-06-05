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
            'menu:site' => [
                'Elgg\Bin\Menus\site::register' => [],
            ],
        ]
    ]
];