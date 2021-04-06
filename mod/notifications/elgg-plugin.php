<?php

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
		],
		'settings:notification:groups' => [
			'path' => '/notifications/group/{username?}',
			'resource' => 'notifications/groups',
			'required_plugins' => [
				'groups',
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
	],
	'events' => [
		'create' => [
			'relationship' => [
				'Elgg\Notifications\Relationships::createFriendNotificationsRelationship' => [],
			],
		],
		'delete' => [
			'relationship' => [
				'Elgg\Notifications\Relationships::deleteFriendNotificationsSubscription' =>[],
			],
		],
	],
	'hooks' => [
		'access:collections:add_user' => [
			'collection' => [
				'Elgg\Notifications\Relationships::updateUserNotificationsPreferencesOnACLChange' => [],
			],
		],
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
];
