<?php

use Elgg\Pages\Controllers\ContentListing;
use Elgg\Pages\Controllers\Entity;
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
			'class' => \ElggPage::class,
			'capabilities' => [
				'commentable' => true,
				'header_image' => true,
				'likable' => true,
				'restorable' => true,
				'river_emittable' => true,
				'searchable' => true,
				'subscribable' => true,
			],
		],
	],
	'actions' => [
		'page/edit' => [
			'controller' => \Elgg\Pages\Controllers\EditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'page',
			],
		],
	],
	'routes' => [
		'action:pages/edit' => [
			'path' => 'action/pages/edit',
			'deprecated' => '7.1', // @todo remove in Elgg 8.0
			'controller' => \Elgg\Pages\Controllers\EditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'page',
			],
			'middleware' => [
				\Elgg\Router\Middleware\CsrfFirewall::class,
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\ActionMiddleware::class,
			],
		],
		'default:object:page' => [
			'path' => '/pages',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'pages/sidebar',
			],
		],
		'collection:object:page:all' => [
			'path' => '/pages/all',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'pages/sidebar',
			],
		],
		'collection:object:page:owner' => [
			'path' => '/pages/owner/{username}',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'pages/sidebar',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:page:friends' => [
			'path' => '/pages/friends/{username}',
			'controller' => ContentListing::class,
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:page:group' => [
			'path' => '/pages/group/{guid}',
			'controller' => ContentListing::class,
			'options' => [
				'group_tool' => 'pages',
				'sidebar_view' => 'pages/sidebar',
			],
			'required_plugins' => [
				'groups',
			],
		],
		'add:object:page' => [
			'path' => '/pages/add/{guid}',
			'controller' => Entity::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'view:object:page' => [
			'path' => '/pages/view/{guid}/{title?}',
			'controller' => Entity::class,
			'options' => [
				'sidebar_view' => 'pages/sidebar/navigation',
			],
		],
		'edit:object:page' => [
			'path' => '/pages/edit/{guid}',
			'controller' => Entity::class,
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
		'entity:url' => [
			'object:widget' => [
				'Elgg\Pages\Widgets::pagesWidgetURL' => [],
			],
		],
		'extender:url' => [
			'annotation' => [
				'Elgg\Pages\Extender::setRevisionUrl' => [],
			],
		],
		'form:prepare:fields' => [
			'page/edit' => [
				PrepareFields::class => [],
			],
		],
		'form:register:fields' => [
			'object:page' => [
				'Elgg\Forms\RegisterFields::addContainerInput' => ['priority' => 600],
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
				'Elgg\Pages\Menus\Title::registerAddSubpage' => [],
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
				'create' =>	[
					CreatePageEventHandler::class => [],
				],
				'mentions' => [
					\Elgg\Notifications\Handlers\Mentions::class => [],
				],
			],
		],
	],
];
