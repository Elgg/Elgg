<?php

use Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper;

return [
	'plugin' => [
		'name' => 'Notifications',
		'activate_on_install' => true,
	],
	'actions' => [
		'notifications/settings' => [],
		'notifications/subscriptions' => [],
	],
	'routes' => [
		'settings:notification:personal' => [
			'path' => '/notifications/personal/{username?}',
			'resource' => 'notifications/index',
			'middleware' => [
				UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'settings:notification:groups' => [
			'path' => '/notifications/group/{username?}',
			'resource' => 'notifications/groups',
			'required_plugins' => [
				'groups',
			],
			'middleware' => [
				UserPageOwnerCanEditGatekeeper::class,
			],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'notifications.css' => [],
		],
		'forms/usersettings/save' => [
			'core/settings/account/notifications' => ['unextend' => true],
		],
		'notifications/settings' => [
			'notifications/users' => [],
		],
	],
	'events' => [
		'create' => [
			'group' => [
				'Elgg\Notifications\Subscriptions::createContent' => [],
			],
			'object' => [
				'Elgg\Notifications\Subscriptions::createContent' => [],
			],
		],
	],
	'hooks' => [
		'register' => [
			'menu:page' => [
				'Elgg\Notifications\Menus\Page::register' => [],
			],
			'menu:title' => [
				'Elgg\Notifications\Menus\Title::register' => [],
			],
		],
		'usersettings:save' => [
			'user' => [
				'Elgg\Notifications\SaveUserSettingsHandler' => ['unregister' => true],
			],
		],
	],
	'upgrades' => [
		\Elgg\Notifications\Upgrades\MigrateACLNotificationPreferences::class,
	],
];
