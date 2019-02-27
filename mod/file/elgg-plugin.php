<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'file',
			'searchable' => true,
		],
	],
	'actions' => [
		'file/upload' => [],
	],
	'routes' => [
		'default:object:file' => [
			'path' => '/file',
			'resource' => 'file/all',
		],
		'collection:object:file:all' => [
			'path' => '/file/all',
			'resource' => 'file/all',
		],
		'collection:object:file:owner' => [
			'path' => '/file/owner/{username}',
			'resource' => 'file/owner',
		],
		'collection:object:file:friends' => [
			'path' => '/file/friends/{username}',
			'resource' => 'file/friends',
		],
		'collection:object:file:group' => [
			'path' => '/file/group/{guid}',
			'resource' => 'file/owner',
		],
		'add:object:file' => [
			'path' => '/file/add/{guid}',
			'resource' => 'file/upload',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'edit:object:file' => [
			'path' => '/file/edit/{guid}',
			'resource' => 'file/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:object:file' => [
			'path' => '/file/view/{guid}/{title?}',
			'resource' => 'file/view',
		],
	],
	'widgets' => [
		'filerepo' => [
			'context' => ['profile', 'dashboard'],
		],
	],
];
