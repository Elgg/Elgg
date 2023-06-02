<?php


return [
    'plugin' => [
            'name' => 'bin',
            'activate_on_install' => true,
    ],
    'routes' => [
                'default:bin' => [
                        'path' => '/bin',
                        'resource' => 'bin',
                ],
        ],
];