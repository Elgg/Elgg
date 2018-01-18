<?php

return [
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
		],
	],
];
