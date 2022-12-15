<?php

use Elgg\Router\Middleware\Gatekeeper;
use Elgg\TheWire\Notifications\CreateTheWireEventHandler;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'The Wire',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'thewire',
			'class' => 'ElggWire',
			'capabilities' => [
				'commentable' => false,
				'searchable' => true,
				'likable' => true,
			],
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
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:thewire:friends' => [
			'path' => '/thewire/friends/{username}',
			'resource' => 'thewire/friends',
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:thewire:thread' => [
			'path' => '/thewire/thread/{guid}',
			'resource' => 'thewire/thread',
			'middleware' => [
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'collection:object:thewire:tag' => [
			'path' => '/thewire/tag/{tag}',
			'resource' => 'thewire/tag',
		],
		'collection:object:thewire:mentions' => [
			'path' => '/thewire/mentions/{username}',
			'resource' => 'thewire/mentions',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
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
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
	],
	'widgets' => [
		'thewire' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'events' => [
		'register' => [
			'menu:entity' => [
				'Elgg\TheWire\Menus\Entity::register' => [],
			],
			'menu:filter:filter' => [
				'Elgg\TheWire\Menus\Filter::registerMentions' => [],
			],
			'menu:owner_block' => [
				'Elgg\TheWire\Menus\OwnerBlock::register' => [],
			],
			'menu:site' => [
				'Elgg\TheWire\Menus\Site::register' => [],
			],
		],
		'seeds' => [
			'database' => [
				'Elgg\TheWire\Seeder::register' => [],
			],
		],
	],
	'notifications' => [
		'object' => [
			'thewire' => [
				'create' => CreateTheWireEventHandler::class,
				'mentions' => \Elgg\Notifications\MentionsEventHandler::class,
			],
		],
	],
];
