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
			'fiveminute' => [
				'Elgg\SiteNotifications\Cron::cleanupSiteNotificationsWithRemovedLinkedEntities' => [],
			],
		],
		'register' => [
			'menu:entity' => [
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
];
