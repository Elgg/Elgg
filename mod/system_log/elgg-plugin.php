<?php

use Elgg\SystemLog\Bootstrap;
use Elgg\SystemLog\Controllers\ClearIpAddresses;

return [
	'bootstrap' => Bootstrap::class,
	'plugin' => [
		'name' => 'System Log',
		'activate_on_install' => true,
	],
	'settings' => [
		'period' => 'never',
		'enable_ip_logging' => true,
	],
	'actions' => [
		'system_log/clear_ip_addresses' => [
			'access' => 'admin',
			'controller' => ClearIpAddresses::class,
		],
	],
	'events' => [
		'all' => [
			'all' => [
				'Elgg\SystemLog\Logger::listen' => ['priority' => 400],
			],
		],
		'cron' => [
			'all' => [
				'Elgg\SystemLog\Cron::rotateLogs' => [],
			],
			'daily' => [
				'Elgg\SystemLog\Cron::deleteLogs' => [],
			],
		],
		'log' => [
			'systemlog' => [
				'Elgg\SystemLog\Logger::log' => ['priority' => 999],
			],
		],
		'register' => [
			'menu:admin_header' => [
				'Elgg\SystemLog\Menus\AdminHeader::register' => [],
			],
			'menu:entity' => [
				'Elgg\SystemLog\Menus\Entity::register' => [],
			],
			'menu:entity:trash' => [
				'Elgg\SystemLog\Menus\Entity::register' => [],
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
	'view_extensions' => [
		'core/settings/statistics' => [
			'core/settings/account/login_history' => [],
		],
	],
];
