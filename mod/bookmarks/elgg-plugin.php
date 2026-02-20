<?php

use Elgg\Bookmarks\Forms\PrepareFields;
use Elgg\Bookmarks\GroupToolContainerLogicCheck;
use Elgg\Bookmarks\Notifications\CreateBookmarksEventHandler;
use Elgg\Controllers\GenericContentListing;

return [
	'plugin' => [
		'name' => 'Bookmarks',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'bookmarks',
			'class' => \ElggBookmark::class,
			'capabilities' => [
				'commentable' => true,
				'river_emittable' => true,
				'searchable' => true,
				'subscribable' => true,
				'likable' => true,
				'restorable' => true,
			],
		],
	],
	'actions' => [
		'bookmarks/edit' => [
			'controller' => \Elgg\Controllers\EntityEditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'bookmarks',
			],
		],
	],
	'routes' => [
		'default:object:bookmarks' => [
			'path' => '/bookmarks',
			'controller' => GenericContentListing::class,
			'options' => [
				'sidebar_view' => 'bookmarks/sidebar',
			],
		],
		'collection:object:bookmarks:all' => [
			'path' => '/bookmarks/all',
			'controller' => GenericContentListing::class,
			'options' => [
				'sidebar_view' => 'bookmarks/sidebar',
			],
		],
		'collection:object:bookmarks:owner' => [
			'path' => '/bookmarks/owner/{username}',
			'controller' => GenericContentListing::class,
			'options' => [
				'sidebar_view' => 'bookmarks/sidebar',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:bookmarks:friends' => [
			'path' => '/bookmarks/friends/{username}',
			'controller' => GenericContentListing::class,
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:bookmarks:group' => [
			'path' => '/bookmarks/group/{guid}',
			'controller' => GenericContentListing::class,
			'options' => [
				'group_tool' => 'bookmarks',
			],
			'required_plugins' => [
				'groups',
			],
		],
		'add:object:bookmarks' => [
			'path' => '/bookmarks/add/{guid}',
			'resource' => 'bookmarks/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'view:object:bookmarks' => [
			'path' => '/bookmarks/view/{guid}/{title?}',
			'resource' => 'bookmarks/view',
		],
		'edit:object:bookmarks' => [
			'path' => '/bookmarks/edit/{guid}',
			'resource' => 'bookmarks/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'bookmarklet:object:bookmarks' => [
			'path' => '/bookmarks/bookmarklet/{guid}',
			'resource' => 'bookmarks/bookmarklet',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'events' => [
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'entity:url' => [
			'object:widget' => [
				'Elgg\Bookmarks\Widgets::widgetURL' => [],
			],
		],
		'form:prepare:fields' => [
			'bookmarks/edit' => [
				PrepareFields::class => [],
			],
		],
		'register' => [
			'menu:footer' => [
				'Elgg\Bookmarks\Menus\Footer::register' => [],
			],
			'menu:owner_block' => [
				'Elgg\Bookmarks\Menus\OwnerBlock::registerUserItem' => [],
				'Elgg\Bookmarks\Menus\OwnerBlock::registerGroupItem' => [],
			],
			'menu:page' => [
				'Elgg\Bookmarks\Menus\Page::register' => [],
			],
			'menu:site' => [
				'Elgg\Bookmarks\Menus\Site::register' => [],
			],
		],
		'seeds' => [
			'database' => [
				'Elgg\Bookmarks\Seeder::register' => [],
			],
		],
	],
	'widgets' => [
		'bookmarks' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'group_tools' => [
		'bookmarks' => [],
	],
	'notifications' => [
		'object' => [
			'bookmarks' => [
				'create' => [
					CreateBookmarksEventHandler::class => [],
				],
				'mentions' => [
					\Elgg\Notifications\Handlers\Mentions::class => [],
				],
			],
		],
	],
];
