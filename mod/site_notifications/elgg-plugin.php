<?php

return [
	'plugin' => [
		'name' => 'Site Notifications',
	],
	'bootstrap' => \Elgg\SiteNotifications\Bootstrap::class,
	'settings' => [
		'unread_cleanup_days' => 365,
		'read_cleanup_days' => 30,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'site_notification',
			'class' => 'SiteNotification',
		],
	],
	'actions' => [
		'site_notifications/mark_read' => [],
		'site_notifications/process' => [],
	],
	'routes' => [
		'collection:object:site_notification:owner' => [
			'path' => '/site_notifications/owner/{username}',
			'resource' => 'site_notifications/owner',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'collection:object:site_notification:read' => [
			'path' => '/site_notifications/read/{username}',
			'resource' => 'site_notifications/read',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'redirect:object:site_notification' => [
			'path' => '/site_notifications/redirect/{guid}',
			'controller' => 'Elgg\SiteNotifications\Controllers\Redirect',
		],
	],
	'hooks' => [
		'cron' => [
			'daily' => [
				'Elgg\SiteNotifications\Cron::cleanupUnreadSiteNotifications' => [],
				'Elgg\SiteNotifications\Cron::cleanupReadSiteNotifications' => [],
			],
			'fiveminute' => [
				'Elgg\SiteNotifications\Cron::cleanupSiteNotificationsWithRemovedLinkedEntities' => [],
			],
		],
		'register' => [
			'menu:entity:object:site_notification' => [
				'Elgg\SiteNotifications\Menus\Entity::register' => [],
			],
			'menu:filter:site_notifications' => [
				'Elgg\SiteNotifications\Menus\Filter::registerTabs' => [],
			],
			'menu:topbar' => [
				'Elgg\SiteNotifications\Menus\Topbar::register' => [],
			],
		],
		'send' => [
			'notification:site' => [
				'Elgg\SiteNotifications\Notifications::createSiteNotifications' => [],
			],
		],
		'view_vars' => [
			'object/elements/full' => [
				'Elgg\SiteNotifications\Views::markLinkedEntityRead' => [],
			],
		],
	],
	'events' => [
		'create' => [
			'user' => [
				'Elgg\SiteNotifications\Users::enableSiteNotifications' => [
					'priority' => 400, // simple way to prevent priority issues with other developers
				],
			],
		],
	],
];
