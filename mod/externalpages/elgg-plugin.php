<?php

return [
	'plugin' => [
		'name' => 'Site Pages',
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'about',
			'class' => 'ElggExternalPage',
			'capabilities' => [
				'commentable' => false,
			],
		],
		[
			'type' => 'object',
			'subtype' => 'terms',
			'class' => 'ElggExternalPage',
			'capabilities' => [
				'commentable' => false,
			],
		],
		[
			'type' => 'object',
			'subtype' => 'privacy',
			'class' => 'ElggExternalPage',
			'capabilities' => [
				'commentable' => false,
			],
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
	'events' => [
		'register' => [
			'menu:admin_header' => [
				'Elgg\ExternalPages\Menus\AdminHeader::register' => [],
			],
			'menu:expages' => [
				'Elgg\ExternalPages\Menus\ExPages::register' => [],
			],
			'menu:footer' => [
				'Elgg\ExternalPages\Menus\Footer::register' => [],
			],
			'menu:walled_garden' => [
				'Elgg\ExternalPages\Menus\WalledGarden::register' => [],
			],
		],
	],
];
