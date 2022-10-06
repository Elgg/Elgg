<?php

return [
	'plugin' => [
		'name' => 'Members',
		'activate_on_install' => true,
	],
	'routes' => [
		'collection:user:user' => [
			'path' => '/members',
			'resource' => 'members/all',
		],
		'collection:user:user:online' => [
			'path' => '/members/online',
			'resource' => 'members/online',
		],
		'collection:user:user:popular' => [
			'path' => '/members/popular',
			'resource' => 'members/popular',
		],
		'search:user:user' => [
			'path' => '/members/search',
			'resource' => 'members/search',
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
