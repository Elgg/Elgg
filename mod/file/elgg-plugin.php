<?php

use Elgg\File\Forms\PrepareFields;
use Elgg\File\GroupToolContainerLogicCheck;
use Elgg\File\Notifications\CreateFileEventHandler;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'File',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'file',
			'capabilities' => [
				'commentable' => true,
				'searchable' => true,
				'likable' => true,
			],
		],
	],
	'upgrades' => [
		'Elgg\File\Upgrades\MoveFiles',
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
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:file:friends' => [
			'path' => '/file/friends/{username}',
			'resource' => 'file/friends',
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:file:group' => [
			'path' => '/file/group/{guid}',
			'resource' => 'file/group',
			'required_plugins' => [
				'groups',
			],
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerGatekeeper::class,
			],
		],
		'add:object:file' => [
			'path' => '/file/add/{guid}',
			'resource' => 'file/upload',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
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
	'events' => [
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'entity:icon:sizes' => [
			'object' => [
				'Elgg\File\Icons::setIconSizes' => [],
			],
		],
		'form:prepare:fields' => [
			'file/upload' => [
				PrepareFields::class => [],
			],
		],
		'register' => [
			'menu:owner_block' => [
				'Elgg\File\Menus\OwnerBlock::registerUserItem' => [],
				'Elgg\File\Menus\OwnerBlock::registerGroupItem' => [],
			],
			'menu:site' => [
				'Elgg\File\Menus\Site::register' => [],
			],
			'menu:title:object:file' => [
				\Elgg\Notifications\RegisterSubscriptionMenuItemsHandler::class => [],
			],
		],
		'seeds' => [
			'database' => [
				'Elgg\File\Seeder::register' => [],
			],
		],
	],
	'widgets' => [
		'filerepo' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'group_tools' => [
		'file' => [],
	],
	'view_extensions' => [
		'elgg.css' => [
			'file/file.css' => [],
		],
		'extensions/item' => [
			'file/enclosure' => [],
		],
		'object/elements/imprint/contents' => [
			'object/file/imprint/filetype' => [],
		],
	],
	'notifications' => [
		'object' => [
			'file' => [
				'create' => CreateFileEventHandler::class,
				'mentions' => \Elgg\Notifications\MentionsEventHandler::class,
			],
		],
	],
];
