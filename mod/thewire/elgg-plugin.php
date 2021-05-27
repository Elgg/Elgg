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
			'required_plugins' => [
				'friends',
			],
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
	],
	'widgets' => [
		'thewire' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'thewire/css' => [],
		],
	],
	'hooks' => [
		'likes:is_likable' => [
			'object:thewire' => [
				'Elgg\Values::getTrue' => [],
			],
		],
		'register' => [
			'menu:entity' => [
				'Elgg\TheWire\Menus\Entity::register' => [],
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
			],
		],
	],
];
