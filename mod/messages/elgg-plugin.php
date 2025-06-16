<?php

use Elgg\Messages\Forms\PrepareFields;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Messages',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'messages',
			'class' => 'ElggMessage',
			'capabilities' => [
				'commentable' => false,
				'searchable' => true,
			],
		],
	],
	'actions' => [
		'messages/send' => [],
		'messages/process' => [],
	],
	'routes' => [
		'collection:object:messages:owner' => [
			'path' => '/messages/inbox/{username}',
			'resource' => 'messages/inbox',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'collection:object:messages:sent' => [
			'path' => '/messages/sent/{username}',
			'resource' => 'messages/sent',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'add:object:messages' => [
			'path' => '/messages/add',
			'resource' => 'messages/send',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:object:messages' => [
			'path' => '/messages/read/{guid}',
			'resource' => 'messages/read',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'messages/css' => [],
		],
	],
	'events' => [
		'delete:after' => [
			'user' => [
				'Elgg\Messages\User::purgeMessages' => [],
			],
		],
		'form:prepare:fields' => [
			'messages/send' => [
				PrepareFields::class => [],
			],
		],
		'register' => [
			'menu:page' => [
				'Elgg\Messages\Menus\Page::register' => [],
			],
			'menu:title' => [
				'Elgg\Messages\Menus\Title::register' => [],
			],
			'menu:topbar' => [
				'Elgg\Messages\Menus\Topbar::register' => [],
			],
			'menu:user_hover' => [
				'Elgg\Messages\Menus\UserHover::register' => [],
			],
		],
	],
	'settings' => [
		'friends_only' => false,
	],
];
