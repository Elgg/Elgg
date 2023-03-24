<?php

use Elgg\Blog\Forms\PrepareFields;
use Elgg\Blog\GroupToolContainerLogicCheck;
use Elgg\Blog\Notifications\PublishBlogEventHandler;

return [
	'plugin' => [
		'name' => 'Blog',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'blog',
			'class' => 'ElggBlog',
			'capabilities' => [
				'commentable' => true,
				'searchable' => true,
				'likable' => true,
			],
		],
	],
	'actions' => [
		'blog/save' => [],
	],
	'routes' => [
		'collection:object:blog:owner' => [
			'path' => '/blog/owner/{username}/{lower?}/{upper?}',
			'resource' => 'blog/owner',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:blog:friends' => [
			'path' => '/blog/friends/{username}/{lower?}/{upper?}',
			'resource' => 'blog/friends',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
			'required_plugins' => [
				'friends',
			],
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'view:object:blog' => [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		],
		'add:object:blog' => [
			'path' => '/blog/add/{guid}',
			'resource' => 'blog/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'edit:object:blog' => [
			'path' => '/blog/edit/{guid}/{revision?}',
			'resource' => 'blog/edit',
			'requirements' => [
				'revision' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:blog:group' => [
			'path' => '/blog/group/{guid}/{subpage?}/{lower?}/{upper?}',
			'resource' => 'blog/group',
			'defaults' => [
				'subpage' => 'all',
			],
			'requirements' => [
				'subpage' => 'all|archive',
				'lower' => '\d+',
				'upper' => '\d+',
			],
			'required_plugins' => [
				'groups',
			],
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerGatekeeper::class,
			],
		],
		'collection:object:blog:all' => [
			'path' => '/blog/all/{lower?}/{upper?}',
			'resource' => 'blog/all',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'default:object:blog' => [
			'path' => '/blog',
			'resource' => 'blog/all',
		],
	],
	'events' => [
		'container_logic_check' => [
			'object' => [
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'form:prepare:fields' => [
			'blog/save' => [
				PrepareFields::class => [],
			],
		],
		'register' => [
			'menu:blog_archive' => [
				'Elgg\Blog\Menus\BlogArchive::register' => [],
			],
			'menu:owner_block' => [
				'Elgg\Blog\Menus\OwnerBlock::registerUserItem' => [],
				'Elgg\Blog\Menus\OwnerBlock::registerGroupItem' => [],
			],
			'menu:site' => [
				'Elgg\Blog\Menus\Site::register' => [],
			],
			'menu:title:object:blog' => [
				\Elgg\Notifications\RegisterSubscriptionMenuItemsHandler::class => [],
			],
		],
		'seeds' => [
			'database' => [
				'Elgg\Blog\Seeder::register' => [],
			],
		],
	],
	'widgets' => [
		'blog' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'group_tools' => [
		'blog' => [],
	],
	'notifications' => [
		'object' => [
			'blog' => [
				'publish' => PublishBlogEventHandler::class,
				'mentions' => \Elgg\Notifications\MentionsEventHandler::class,
			],
		],
	],
];
