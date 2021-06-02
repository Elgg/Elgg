<?php

return [
	'plugin' => [
		'name' => 'User Dashboard',
	],
	'routes' => [
		'default:dashboard' => [
			'path' => '/dashboard',
			'resource' => 'dashboard',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'events' => [
		'login:first' => [
			'user' => [
				\Elgg\Widgets\CreateDefaultWidgetsHandler::class => [],
			],
		],
	],
	'hooks' => [
		'get_list' => [
			'default_widgets' => [
				'Elgg\Dashboard\Widgets::extendDefaultWidgetsList' => [],
			],
		],
		'register' => [
			'menu:topbar' => [
				'Elgg\Dashboard\Menus\Topbar::register' => [],
			],
		],
	],
];
