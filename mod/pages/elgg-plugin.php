<?php

use Elgg\Pages\Forms\PrepareFields;
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
			'class' => '\ElggPage',
			'capabilities' => [
				'commentable' => true,
				'searchable' => true,
				'likable' => true,
			],
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
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:page:friends' => [
			'path' => '/pages/friends/{username}',
			'resource' => 'pages/friends',
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:page:group' => [
			'path' => '/pages/group/{guid}/{subpage?}',
			'resource' => 'pages/group',
			'required_plugins' => [
				'groups',
			],
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerGatekeeper::class,
			],
		],
		'add:object:page' => [
			'path' => '/pages/add/{guid}',
			'resource' => 'pages/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
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
			'middleware' => [
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'revision:object:page' => [
			'path' => '/pages/revision/{id}',
			'resource' => 'pages/revision',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'events' => [
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
		'form:prepare:fields' => [
			'pages/edit' => [
				PrepareFields::class => [],
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
				'mentions' => \Elgg\Notifications\MentionsEventHandler::class,
			],
		],
	],
];
