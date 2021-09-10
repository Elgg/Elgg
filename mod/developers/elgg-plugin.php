<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Elgg Developer Tools',
	],
	'bootstrap' => \Elgg\Developers\Bootstrap::class,
	'actions' => [
		'developers/settings' => [
			'access' => 'admin',
		],
		'developers/ajax_demo' => [
			'access' => 'admin',
		],
		'developers/entity_explorer_delete' => [
			'access' => 'admin',
		],
		'developers/test_email' => [],
	],
	'routes' => [
		'default:theme_sandbox' => [
			'path' => '/theme_sandbox/{page?}',
			'resource' => 'theme_sandbox',
			'defaults' => [
				'page' => 'intro',
			],
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
			],
		],
		'default:developers:ajax_demo' => [
			'path' => '/developers_ajax_demo',
			'resource' => 'developers/ajax_demo',
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
			],
		],
		'default:developers:email' => [
			'path' => '/developers_email',
			'resource' => 'developers/email',
			'middleware' => [
				\Elgg\Router\Middleware\AdminGatekeeper::class,
			],
		],
	],
	'hooks' => [
		'register' => [
			'menu:entity' => [
				'Elgg\Developers\Menus\Entity::registerEntityExplorer' => [],
			],
			'menu:page' => [
				'Elgg\Developers\Menus\Page::register' => [],
			],
		],
	],
	'view_options' => [
		'developers/ajax' => ['ajax' => true],
		'developers/ajax_demo.html' => ['ajax' => true],
		'forms/developers/ajax_demo' => ['ajax' => true],
		'theme_sandbox/components/tabs/ajax' => ['ajax' => true],
	],
	'view_extensions' => [
		'admin.css' => [
			'admin/develop_tools/error_log.css' => [],
			'developers/css' => [],
		],
		'elgg.css' => [
			'admin/develop_tools/error_log.css' => [],
			'developers/css' => [],
		],
	],
];
