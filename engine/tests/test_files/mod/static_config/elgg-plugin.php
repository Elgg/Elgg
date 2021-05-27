<?php

return [
	'actions' => [
		'static_config/autodetect' => [],
		'static_config/custom_file' => [
			'filename' => __DIR__ . '/actions/custom_file.php',
			'access' => 'public',
		],
		'static_config/controller' => [
			'controller' => \Elgg\StaticConfig\ActionController::class,
			'access' => 'admin',
		],
	],
	'bootstrap' => \Elgg\StaticConfig\Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'static_config_subtype',
			'class' => \StaticConfigObject::class,
			'searchable' => true,
		],
	],
	'events' => [
		'do' => [
			'something' => [
				'Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::eventCallback' => [
					'unregister' => true,
				],
				'\Elgg\StaticConfig\EventCallback::highPriority' => [
					'priority' => 900,
				],
				'\Elgg\StaticConfig\EventCallback' => [
					'priority' => 100,
				],
			],
		],
	],
	'group_tools' => [
		'static_config_unregister' => [
			'unregister' => true,
		],
		'static_config' => [],
	],
	'hooks' => [
		'prevent' => [
			'something' => [
				'Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::hookCallback' => [
					'unregister' => true,
				],
				'\Elgg\StaticConfig\HookCallback::highPriority' => [
					'priority' => 900,
				],
				'\Elgg\StaticConfig\HookCallback' => [
					'priority' => 100,
				],
			],
		],
	],
	'notifications' => [
		'object' => [
			'static_config_subtype' => [
				'create' => true,
				'update' => false,
			],
		],
	],
	'routes' => [
		'default:object:static_config_subtype' => [
			'path' => '/static_config',
			'resource' => 'static_config/all',
		],
	],
	'views' => [
		'default' => [
			'custom_directory/' => __DIR__ . '/custom_views/custom_directory/',
			'custom_view' => __DIR__ . '/custom_views/custom_view.php',
		],
	],
	'view_extensions' => [
		'static_config/view' => [
			'static_config/extension900' => [
				'priority' => 900,
			],
			'static_config/extension100' => [
				'priority' => 100,
			],
			'static_config/unextend' => [
				'unextend' => true,
			],
		],
	],
	'view_options' => [
		'static_config/viewoptions' => [
			'ajax' => true,
			'simplecache' => true,
		],
		'static_config/view' => [
			'ajax' => false,
		],
	],
	'widgets' => [
		'static_config' => [
			'context' => ['profile'],
		],
	],
];
