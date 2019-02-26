<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'messages',
			'class' => 'ElggMessage',
			'searchable' => false,
		],
	],
	'actions' => [
		'messages/send' => [],
		'messages/delete' => [],
		'messages/process' => [],
	],
	'routes' => [
		'collection:object:messages:owner' => [
			'path' => '/messages/inbox/{username}',
			'resource' => 'messages/inbox',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:messages:sent' => [
			'path' => '/messages/sent/{username}',
			'resource' => 'messages/sent',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'add:object:messages' => [
			'path' => '/messages/add/{container_guid?}',
			'resource' => 'messages/send',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:object:messages' => [
			'path' => '/messages/read/{guid}',
			'resource' => 'messages/read',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
