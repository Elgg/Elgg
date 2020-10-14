<?php

use Elgg\Router\Middleware\Gatekeeper;

require_once(__DIR__ . '/lib/functions.php');

return [
	'bootstrap' => \Elgg\TheWire\Bootstrap::class,
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
		'enable_editing' => true,
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
		'edit:object:thewire' => [
			'path' => '/thewire/edit/{guid}',
			'resource' => 'thewire/edit',
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
	'view_options' => [
		'thewire/previous' => ['ajax' => true],
	],
	'hooks' => [
		'get' => [
			'subscriptions' => [
				'Elgg\TheWire\Notifications::addOriginalPoster' => [],
			],
		],
		'likes:is_likable' => [
			'object:thewire' => [
				'Elgg\Values::getTrue' => [],
			],
		],
		'prepare' => [
			'notification:create:object:thewire' => [
				'Elgg\TheWire\Notifications::prepareCreateTheWireNotification' => [],
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
];
