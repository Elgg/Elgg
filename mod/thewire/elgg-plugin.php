<?php

use Elgg\TheWire\Controllers\ContentListing;
use Elgg\TheWire\Notifications\CreateTheWireEventHandler;

return [
	'plugin' => [
		'name' => 'The Wire',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'thewire',
			'class' => \ElggWire::class,
			'capabilities' => [
				'river_emittable' => true,
				'searchable' => true,
				'likable' => true,
			],
		],
	],
	'settings' => [
		'limit' => 140,
	],
	'actions' => [
		'thewire/add' => [
			'controller' => \Elgg\TheWire\Controllers\EditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'thewire',
			],
		],
	],
	'routes' => [
		'default:object:thewire' => [
			'path' => '/thewire',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'thewire/sidebar',
			],
		],
		'collection:object:thewire:all' => [
			'path' => '/thewire/all',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'thewire/sidebar',
			],
		],
		'collection:object:thewire:owner' => [
			'path' => '/thewire/owner/{username}',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'thewire/sidebar',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:thewire:friends' => [
			'path' => '/thewire/friends/{username}',
			'controller' => ContentListing::class,
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:thewire:thread' => [
			'path' => '/thewire/thread/{guid}',
			'controller' => ContentListing::class,
		],
		'collection:object:thewire:tag' => [
			'path' => '/thewire/tag/{tag}',
			'controller' => ContentListing::class,
		],
		'collection:object:thewire:mentions' => [
			'path' => '/thewire/mentions/{username}',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'thewire/sidebar',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
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
				\Elgg\Router\Middleware\Gatekeeper::class,
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
		'entity:url' => [
			'object:widget' => [
				'Elgg\TheWire\Widgets::thewireWidgetURL' => [],
			],
		],
		'prepare' => [
			'html' => [
				'Elgg\TheWire\Views::parseTags' => [],
			],
		],
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
