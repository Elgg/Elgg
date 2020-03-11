<?php

return [
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
		'create' => [
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
