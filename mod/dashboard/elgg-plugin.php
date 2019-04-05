<?php

return [
	'routes' => [
		'default:dashboard' => [
			'path' => '/dashboard',
			'resource' => 'dashboard',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
