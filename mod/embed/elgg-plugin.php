<?php

return [
	'routes' => [
		'default:embed' => [
			'path' => '/embed/{tab?}',
			'resource' => 'embed/embed',
			'requirements' => [
				'tab' => '\w+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\AjaxGatekeeper::class,
			],
		],
	],
];
