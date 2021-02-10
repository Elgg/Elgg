<?php

return [
	'bootstrap' => \Elgg\SiteNotifications\Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'site_notification',
			'class' => 'SiteNotification',
		],
	],
	'actions' => [
		'site_notifications/process' => [],
	],
	'routes' => [
		'collection:object:site_notification:owner' => [
			'path' => '/site_notifications/owner/{username}',
			'resource' => 'site_notifications/owner',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'hooks' => [
		'cron' => [
			'fiveminute' => [
				'Elgg\SiteNotifications\Cron::cleanupSiteNotificationsWithRemovedLinkedEntities' => [],
			],
		],
		'register' => [
			'menu:entity' => [
				'Elgg\SiteNotifications\Menus\Entity::register' => [],
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
	],
];
