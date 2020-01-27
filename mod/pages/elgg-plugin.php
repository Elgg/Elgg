<?php

use Elgg\Pages\GroupToolContainerLogicCheck;

return [
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
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
	],
	'widgets' => [
		'pages' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'upgrades' => [
		'\Elgg\Pages\Upgrades\MigratePageTop',
	],
];
