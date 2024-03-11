<?php

use Elgg\Router\Middleware\Gatekeeper;
use Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper;

return [
	'plugin' => [
		'name' => 'Bin',
	],
	'routes' => [
		'default:bin' => [
			'path' => '/bin/{username}',
			'resource' => 'bin/bin',
			'middleware' => [
				Gatekeeper::class,
				UserPageOwnerCanEditGatekeeper::class
			],
		],
	],
	'events' => [
		'register' => [
			'menu:topbar' => [
				'Elgg\Bin\Menus\Topbar::register' => [],
			],
		]
	]
];
