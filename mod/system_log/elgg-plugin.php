<?php

return [
	'bootstrap' => Elgg\SystemLog\Bootstrap::class,
	'plugin' => [
		'name' => 'System Log',
		'activate_on_install' => true,
	],
	'settings' => [
		'period' => 'never',
		'delete' => 'never',
	],
	'view_extensions' => [
		'core/settings/statistics' => [
			'core/settings/account/login_history' => [],
		],
	],
	'events' => [
		'all' => [
			'all' => [
				'Elgg\SystemLog\Logger::listen' => ['priority' => 400],
			],
		],
		'log' => [
			'systemlog' => [
				'Elgg\SystemLog\Logger::log' => ['priority' => 999],
			],
		],
		'upgrade:before' => [
			'system' => [
				'Elgg\SystemLog\Logger::disableLogging' => [],
			],
		],
		'upgrade:execute:before' => [
			'system' => [
				'Elgg\SystemLog\Logger::disableLogging' => [],
			],
		],
	],
	'hooks' => [
		'cron' => [
			'all' => [
				'Elgg\SystemLog\Cron::rotateLogs' => [],
				'Elgg\SystemLog\Cron::deleteLogs' => [],
			],
		],
		'register' => [
			'menu:page' => [
				'Elgg\SystemLog\Menus\Page::register' => [],
			],
			'menu:user_hover' => [
				'Elgg\SystemLog\Menus\UserHover::register' => [],
			],
		],
	],
];
