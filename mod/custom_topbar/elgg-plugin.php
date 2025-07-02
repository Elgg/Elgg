<?php

/**
 * Custom topbar
 * @author Nikolai Shcherbin
 * @license GNU Affero General Public License version 3
 * @copyright (c) Nikolai Shcherbin 2025
 * @link https://wzm.me
**/

return [
    'plugin' => [
        'name' => 'Custom topbar',
        'version' => '1.0.0',
        'activate_on_install' => true,
    ],

    'bootstrap' => \wZm\CustomTopbar\Bootstrap::class,

    'actions' => [
        'admin/topbar/logo' => [
            'controller' => \wZm\CustomTopbar\Actions\SaveLogoAction::class,
            'access' => 'admin',
        ],
    ],

    'events' => [
        'register' => [
            'menu:admin_header' => [
                \wZm\CustomTopbar\Menus\AdminHeader::class => [],
            ],
            'menu:topbar' => [
                \wZm\CustomTopbar\Menus\Topbar::class => [],
            ],
        ],
    ],

    'view_extensions' => [
        'admin.css' => [
            'topbar/admin.css' => [],
        ],
        'elements/layout/topbar.css' => [
            'topbar/topbar.css' => ['priority' => 800],
        ],
    ],

    'views' => [
        'default' => [
            'assets/' => elgg_get_data_path() . 'assets/',
        ],
        'json' => [
            'assets/' => elgg_get_data_path() . 'assets/',
        ],
    ],
];
