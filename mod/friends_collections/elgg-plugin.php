<?php
use Elgg\Friends\Collections\CollectionMenuHandler;
use Elgg\Friends\Collections\DeleteRelationshipHandler;
use Elgg\Friends\Collections\EntityMenuHandler;
use Elgg\Friends\Collections\PageMenuHandler;
use Elgg\Friends\Collections\UrlHandler;
use Elgg\Friends\Collections\WriteAccess;

return [
	'plugin' => [
		'name' => 'Friend Collections',
		'activate_on_install' => true,
		'dependencies' => [
			'friends' => [],
		],
	],
	'actions' => [
		'friends/collections/edit' => [],
		'friends/collections/delete' => [],
		'friends/collections/remove_member' => [],
	],
	'routes' => [
		'add:access_collection:friends' => [
			'path' => '/friends/collections/add/{username?}',
			'resource' => 'friends/collections/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'edit:access_collection:friends' => [
			'path' => '/friends/collections/edit/{collection_id}',
			'resource' => 'friends/collections/edit',
			'requirements' => [
				'collection_id' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:access_collection:friends' => [
			'path' => '/friends/collections/view/{collection_id}',
			'resource' => 'friends/collections/view',
			'requirements' => [
				'collection_id' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:access_collection:friends:owner' => [
			'path' => '/friends/collections/owner/{username?}',
			'resource' => 'friends/collections/owner',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'events' => [
		'delete' => [
			'relationship' => [
				DeleteRelationshipHandler::class => [],
			],
		],
	],
	'hooks' => [
		'access_collection:url' => [
			'access_collection' => [
				UrlHandler::class => [],
			],
		],
		'access:collections:write:subtypes' => [
			'user' => [
				WriteAccess::class => [],
			],
		],
		'register' => [
			'menu:entity:user:user' => [
				EntityMenuHandler::class => [],
			],
			'menu:friends:collection' => [
				CollectionMenuHandler::class => [],
			],
			'menu:page' => [
				PageMenuHandler::class => [],
			],
		],
	],
];
