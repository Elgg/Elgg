<?php

return [
	'actions' => [
		'friends/invite' => [],
	],
	'routes' => [
		'default:user:user:invite' => [
			'path' => '/friends/{username}/invite',
			'resource' => 'friends/invite',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
