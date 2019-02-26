<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'site_notification',
			'class' => 'SiteNotification',
		],
	],
	'actions' => [
		'site_notifications/delete' => [],
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
];
