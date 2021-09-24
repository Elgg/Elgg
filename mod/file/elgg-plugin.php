<?php

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
			'required_plugins' => [
				'friends',
			],
		],
		'collection:object:file:group' => [
			'path' => '/file/group/{guid}',
			'resource' => 'file/group',
			'required_plugins' => [
				'groups',
			],
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
	'hooks' => [
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'entity:icon:file' => [
			'object' => [
				'Elgg\File\Icons::setIconFile' => [],
			],
		],
		'entity:icon:sizes' => [
			'object' => [
				'Elgg\File\Icons::setIconSizes' => [],
			],
		],
		'entity:icon:url' => [
			'object' => [
				'Elgg\File\Icons::setIconUrl' => [],
			],
		],
		'likes:is_likable' => [
			'object:file' => [
				'Elgg\Values::getTrue' => [],
			],
		],
		'register' => [
			'menu:embed' => [
				'Elgg\File\Menus\Embed::registerFile' => [],
				'Elgg\File\Menus\Embed::registerFileUpload' => [],
			],
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
	'events' => [
		'delete' => [
			'object' => [
				'Elgg\File\Icons::deleteIconOnElggFileDelete' => ['priority' => 999],
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
		'theme_sandbox/icons' => [
			'file/theme_sandbox/icons/files' => [],
		],
	],
	'notifications' => [
		'object' => [
			'file' => [
				'create' => CreateFileEventHandler::class,
			],
		],
	],
];
