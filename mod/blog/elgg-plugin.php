<?php

use Elgg\Blog\Controllers\ContentListing;
use Elgg\Blog\Controllers\EditAction;
use Elgg\Blog\Controllers\Entity;
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
			'class' => \ElggBlog::class,
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
		'blog/edit' => [
			'controller' => EditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'blog',
			],
		],
	],
	'routes' => [
		'collection:object:blog:owner' => [
			'path' => '/blog/owner/{username}/{lower?}/{upper?}',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'blog/sidebar',
			],
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
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'blog/sidebar',
			],
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
			'controller' => Entity::class,
			'options' => [
				'sidebar_view' => 'object/blog/elements/sidebar',
			],
		],
		'add:object:blog' => [
			'path' => '/blog/add/{guid}',
			'controller' => Entity::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'edit:object:blog' => [
			'path' => '/blog/edit/{guid}/{revision?}',
			'controller' => Entity::class,
			'options' => [
				'sidebar_view' => 'blog/sidebar/revisions',
			],
			'requirements' => [
				'revision' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:blog:group' => [
			'path' => '/blog/group/{guid}/{lower?}/{upper?}',
			'controller' => ContentListing::class,
			'options' => [
				'group_tool' => 'blog',
				'sidebar_view' => 'blog/sidebar',
			],
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
			'required_plugins' => [
				'groups',
			],
		],
		'collection:object:blog:all' => [
			'path' => '/blog/all/{lower?}/{upper?}',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'blog/sidebar',
			],
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'default:object:blog' => [
			'path' => '/blog',
			'controller' => ContentListing::class,
			'options' => [
				'sidebar_view' => 'blog/sidebar',
			],
		],
	],
	'events' => [
		'container_logic_check' => [
			'object' => [
				'Elgg\Blog\Permissions::preventCommentsWhenDisabledOnBlog' => [],
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'entity:url' => [
			'object:widget' => [
				'Elgg\Blog\Widgets::blogWidgetUrl' => [],
			],
		],
		'form:prepare:fields' => [
			'blog/edit' => [
				PrepareFields::class => [],
			],
		],
		'form:register:fields' => [
			'object:blog' => [
				'Elgg\Forms\RegisterFields::addContainerInput' => ['priority' => 600],
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
				'publish' => [
					PublishBlogEventHandler::class => [],
				],
				'mentions' => [
					\Elgg\Notifications\Handlers\Mentions::class => [],
				],
			],
		],
	],
];
