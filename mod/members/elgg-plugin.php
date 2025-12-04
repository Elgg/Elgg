<?php

use Elgg\Members\Controllers\ListMembers;

return [
	'plugin' => [
		'name' => 'Members',
		'activate_on_install' => true,
	],
	'routes' => [
		'collection:user:user:all' => [
			'path' => '/members',
			'controller' => ListMembers::class,
			'options' => [
				'sidebar_view' => 'members/sidebar',
			],
		],
		'collection:user:user:online' => [
			'path' => '/members/online',
			'controller' => ListMembers::class,
			'options' => [
				'sidebar_view' => 'members/sidebar',
			],
		],
		'collection:user:user:popular' => [
			'path' => '/members/popular',
			'controller' => ListMembers::class,
			'options' => [
				'sidebar_view' => 'members/sidebar',
			],
		],
		'collection:user:user:search' => [
			'path' => '/members/search',
			'controller' => ListMembers::class,
			'options' => [
				'sidebar_view' => 'members/sidebar',
			],
		],
	],
	'events' => [
		'register' => [
			'menu:filter:members' => [
				'Elgg\Members\Menus\Members::register' => [],
				'Elgg\Menus\FilterSortItems::registerTimeCreatedSorting' => [],
				'Elgg\Menus\FilterSortItems::registerNameSorting' => [],
				'Elgg\Menus\FilterSortItems::registerSortingDropdown' => ['priority' => 9999],
			],
			'menu:site' => [
				'Elgg\Members\Menus\Site::register' => [],
			],
			'menu:title' => [
				'Elgg\Members\Menus\Title::register' => [],
			],
		],
	],
];
