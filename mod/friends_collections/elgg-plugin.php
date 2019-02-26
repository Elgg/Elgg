<?php

return [
	'actions' => [
		'friends/collections/edit' => [],
		'friends/collections/delete' => [],
		'friends/collections/remove_member' => [],
	],
	'routes' => [
		'add:access_collection:friends' => [
			'path' => '/friends/collections/add/{username?}',
			'resource' => 'friends/collections/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'edit:access_collection:friends' => [
			'path' => '/friends/collections/edit/{collection_id}',
			'resource' => 'friends/collections/edit',
			'requirements' => [
				'collection_id' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:access_collection:friends' => [
			'path' => '/friends/collections/view/{collection_id}',
			'resource' => 'friends/collections/view',
			'requirements' => [
				'collection_id' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:access_collection:friends:owner' => [
			'path' => '/friends/collections/owner/{username?}',
			'resource' => 'friends/collections/owner',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
