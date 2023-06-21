<?php

return [
	'plugin' => [
		'name' => 'Theme Sandbox',
	],
	'actions' => [
		'theme_sandbox/ajax_demo' => [
			'access' => 'admin',
		],
		'theme_sandbox/test_email' => [],
	],
	'routes' => [
		'default:theme_sandbox' => [
			'path' => '/theme_sandbox/{page?}',
			'resource' => 'theme_sandbox/index',
			'defaults' => [
				'page' => 'intro',
			],
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
			],
		],
		'default:theme_sandbox:ajax_demo' => [
			'path' => '/theme_sandbox_ajax_demo',
			'resource' => 'theme_sandbox/ajax_demo',
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
			],
		],
		'default:theme_sandbox:email' => [
			'path' => '/theme_sandbox_email',
			'resource' => 'theme_sandbox/email',
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
			],
		],
	],
	'events' => [
		'register' => [
			'menu:admin_header' => [
				'Elgg\ThemeSandbox\Menus\AdminHeader::register' => [],
			],
		],
	],
	'view_options' => [
		'theme_sandbox/demo/ajax' => ['ajax' => true],
		'theme_sandbox/demo/ajax_demo.html' => ['ajax' => true],
		'forms/theme_sandbox/ajax_demo' => ['ajax' => true],
		'theme_sandbox/components/tabs/ajax' => ['ajax' => true],
	],
];
