<?php

use Elgg\Bookmarks\GroupToolContainerLogicCheck;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Bookmarks',
		'activate_on_install' => true,
	],
	'bootstrap' => \Elgg\Bookmarks\Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'bookmarks',
			'class' => 'ElggBookmark',
			'searchable' => true,
		],
	],
	'actions' => [
		'bookmarks/save' => [],
	],
	'routes' => [
		'default:object:bookmarks' => [
			'path' => '/bookmarks',
			'resource' => 'bookmarks/all',
		],
		'collection:object:bookmarks:all' => [
			'path' => '/bookmarks/all',
			'resource' => 'bookmarks/all',
		],
		'collection:object:bookmarks:owner' => [
			'path' => '/bookmarks/owner/{username}',
			'resource' => 'bookmarks/owner',
		],
		'collection:object:bookmarks:friends' => [
			'path' => '/bookmarks/friends/{username}',
			'resource' => 'bookmarks/friends',
			'required_plugins' => [
				'friends',
			],
		],
		'collection:object:bookmarks:group' => [
			'path' => '/bookmarks/group/{guid}/{subpage?}',
			'resource' => 'bookmarks/group',
			'defaults' => [
				'subpage' => 'all',
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
	'hooks' => [
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'likes:is_likable' => [
			'object:bookmarks' => [
				'Elgg\Values::getTrue' => [],
			],
		],
		'prepare' => [
			'notification:create:object:bookmarks' => [
				'Elgg\Bookmarks\Notifications::prepareCreateBookmark' => [],
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
	'view_extensions' => [
		'elgg.js' => [
			'bookmarks.js' => [],
		],
	],
];
