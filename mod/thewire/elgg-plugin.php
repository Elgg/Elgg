<?php

use Elgg\Router\Middleware\Gatekeeper;

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'thewire',
			'class' => 'ElggWire',
			'searchable' => true,
		],
	],
	'settings' => [
		'limit' => 140,
	],
	'actions' => [
		'thewire/add' => [],
		'thewire/delete' => [],
	],
	'routes' => [
		'default:object:thewire' => [
			'path' => '/thewire',
			'resource' => 'thewire/all',
		],
		'collection:object:thewire:all' => [
			'path' => '/thewire/all',
			'resource' => 'thewire/all',
		],
		'collection:object:thewire:owner' => [
			'path' => '/thewire/owner/{username}',
			'resource' => 'thewire/owner',
		],
		'collection:object:thewire:friends' => [
			'path' => '/thewire/friends/{username}',
			'resource' => 'thewire/friends',
		],
		'collection:object:thewire:thread' => [
			'path' => '/thewire/thread/{guid}',
			'resource' => 'thewire/thread',
		],
		'collection:object:thewire:tag' => [
			'path' => '/thewire/tag/{tag}',
			'resource' => 'thewire/tag',
		],
		'view:object:thewire' => [
			'path' => '/thewire/view/{guid}',
			'resource' => 'thewire/view',
		],
		'reply:object:thewire' => [
			'path' => '/thewire/reply/{guid}',
			'resource' => 'thewire/reply',
			'middleware' => [
				Gatekeeper::class,
			],
		],
		'previous:object:thewire' => [
			'path' => '/thewire/previous/{guid}',
			'resource' => 'thewire/previous',
		],
	],
	'widgets' => [
		'thewire' => [
			'context' => ['profile', 'dashboard'],
		],
	],
];
