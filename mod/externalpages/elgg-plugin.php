<?php

return [
	'plugin' => [
		'name' => 'Site Pages',
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'about',
			'searchable' => false,
		],
		[
			'type' => 'object',
			'subtype' => 'terms',
			'searchable' => false,
		],
		[
			'type' => 'object',
			'subtype' => 'privacy',
			'searchable' => false,
		],
	],
	'routes' => [
		'view:object:about' => [
			'path' => '/about',
			'resource' => 'expages',
			'defaults' => [
				'expage' => 'about',
			],
			'walled' => false,
		],
		'view:object:privacy' => [
			'path' => '/privacy',
			'resource' => 'expages',
			'defaults' => [
				'expage' => 'privacy',
			],
			'walled' => false,
		],
		'view:object:terms' => [
			'path' => '/terms',
			'resource' => 'expages',
			'defaults' => [
				'expage' => 'terms',
			],
			'walled' => false,
		],
	],
	'actions' => [
		'expages/edit' => [
			'access' => 'admin',
		],
	],
	'hooks' => [
		'register' => [
			'menu:expages' => [
				'Elgg\ExternalPages\Menus\ExPages::register' => [],
			],
			'menu:footer' => [
				'Elgg\ExternalPages\Menus\Footer::register' => [],
			],
			'menu:page' => [
				'Elgg\ExternalPages\Menus\Page::register' => [],
			],
			'menu:walled_garden' => [
				'Elgg\ExternalPages\Menus\WalledGarden::register' => [],
			],
		],
	],
];
