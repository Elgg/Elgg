<?php

use Elgg\Pages\GroupToolContainerLogicCheck;
use Elgg\Pages\Notifications\CreatePageEventHandler;

require_once(__DIR__ . '/lib/pages.php');

return [
	'plugin' => [
		'name' => 'Pages',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'page',
			'searchable' => true,
			'class' => '\ElggPage',
		],
	],
	'actions' => [
		'pages/edit' => [],
	],
	'routes' => [
		'default:object:page' => [
			'path' => '/pages',
			'resource' => 'pages/all',
		],
		'collection:object:page:all' => [
			'path' => '/pages/all',
			'resource' => 'pages/all',
		],
		'collection:object:page:owner' => [
			'path' => '/pages/owner/{username}',
			'resource' => 'pages/owner',
		],
		'collection:object:page:friends' => [
			'path' => '/pages/friends/{username}',
			'resource' => 'pages/friends',
			'required_plugins' => [
				'friends',
			],
		],
		'collection:object:page:group' => [
			'path' => '/pages/group/{guid}/{subpage?}',
			'resource' => 'pages/group',
			'required_plugins' => [
				'groups',
			],
		],
		'add:object:page' => [
			'path' => '/pages/add/{guid}',
			'resource' => 'pages/new',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:object:page' => [
			'path' => '/pages/view/{guid}/{title?}',
			'resource' => 'pages/view',
		],
		'edit:object:page' => [
			'path' => '/pages/edit/{guid}',
			'resource' => 'pages/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'history:object:page' => [
			'path' => '/pages/history/{guid}',
			'resource' => 'pages/history',
		],
		'revision:object:page' => [
			'path' => '/pages/revision/{id}',
			'resource' => 'pages/revision',
		],
	],
	'hooks' => [
		'access:collections:write' => [
			'user' => [
				'Elgg\Pages\Views::removeAccessPublic' => [],
			],
		],
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'container_permissions_check' => [
			'object' => [
				'Elgg\Pages\Permissions::allowContainerWriteAccess' => [],
			],
		],
		'entity:icon:url' => [
			'object' => [
				'Elgg\Pages\Icons::getIconUrl' => [],
			],
		],
		'extender:url' => [
			'annotation' => [
				'Elgg\Pages\Extender::setRevisionUrl' => [],
			],
		],
		'fields' => [
			'object:page' => [
				\Elgg\Pages\FieldsHandler::class => [],
			],
		],
		'likes:is_likable' => [
			'object:page' => [
				'Elgg\Values::getTrue' => [],
			],
		],
		'permissions_check' => [
			'object' => [
				'Elgg\Pages\Permissions::allowWriteAccess' => [],
			],
		],
		'register' => [
			'menu:entity:object:page' => [
				'Elgg\Pages\Menus\Entity::register' => [],
			],
			'menu:owner_block' => [
				'Elgg\Pages\Menus\OwnerBlock::registerUserItem' => [],
				'Elgg\Pages\Menus\OwnerBlock::registerGroupItem' => [],
			],
			'menu:pages_nav' => [
				'Elgg\Pages\Menus\PagesNav::register' => [],
			],
			'menu:site' => [
				'Elgg\Pages\Menus\Site::register' => [],
			],
			'menu:title:object:page' => [
				\Elgg\Notifications\RegisterSubscriptionMenuItemsHandler::class => [],
			],
		],
		'seeds' => [
			'database' => [
				'Elgg\Pages\Seeder::register' => [],
			],
		],
		'view_vars' => [
			'input/access' => [
				'Elgg\Pages\Views::preventAccessPublic' => [],
			],
		],
	],
	'widgets' => [
		'pages' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'group_tools' => [
		'pages' => [],
	],
	'notifications' => [
		'object' => [
			'page' => [
				'create' => CreatePageEventHandler::class,
			],
		],
	],
];
