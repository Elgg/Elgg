<?php

use Elgg\Discussions\Controllers\ContentListing;
use Elgg\Discussions\Forms\PrepareFields;
use Elgg\Discussions\GroupToolContainerLogicCheck;
use Elgg\Discussions\Notifications\CreateDiscussionEventHandler;

return [
	'plugin' => [
		'name' => 'Discussions',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'discussion',
			'class' => \ElggDiscussion::class,
			'capabilities' => [
				'commentable' => true,
				'river_emittable' => true,
				'searchable' => true,
				'subscribable' => true,
				'likable' => true,
				'restorable' => true,
			],
		],
	],
	'settings' => [
		'enable_global_discussions' => 0,
	],
	'actions' => [
		'discussion/edit' => [
			'controller' => \Elgg\Discussions\Controllers\EditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'discussion',
			],
		],
		'discussion/toggle_status' => [],
	],
	'routes' => [
		'default:object:discussion' => [
			'path' => '/discussion',
			'controller' => ContentListing::class,
		],
		'collection:object:discussion:all' => [
			'path' => '/discussion/all',
			'controller' => ContentListing::class,
		],
		'collection:object:discussion:owner' => [
			'path' => '/discussion/owner/{username}',
			'controller' => ContentListing::class,
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:discussion:my_groups' => [
			'path' => '/discussion/my_groups/{username}',
			'controller' => ContentListing::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'collection:object:discussion:group' => [
			'path' => '/discussion/group/{guid}',
			'controller' => ContentListing::class,
			'options' => [
				'group_tool' => 'forum',
			],
			'required_plugins' => [
				'groups',
			],
		],
		'add:object:discussion' => [
			'path' => '/discussion/add/{guid}',
			'resource' => 'discussion/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\PageOwnerGatekeeper::class,
			],
		],
		'edit:object:discussion' => [
			'path' => '/discussion/edit/{guid}',
			'resource' => 'discussion/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:object:discussion' => [
			'path' => '/discussion/view/{guid}/{title?}',
			'resource' => 'discussion/view',
		],
	],
	'events' => [
		'container_logic_check' => [
			'object' => [
				'Elgg\Discussions\Permissions::containerLogic' => [],
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'cron' => [
			'daily' => [
				'Elgg\Discussions\Cron::autoClose' => [],
			],
		],
		'entity:url' => [
			'object:widget' => [
				'Elgg\Discussions\Widgets::widgetURL' => [],
			],
		],
		'form:prepare:fields' => [
			'discussion/edit' => [
				PrepareFields::class => [],
			],
		],
		'get' => [
			'subscriptions' => [
				'Elgg\Discussions\Notifications::addGroupSubscribersToCommentOnDiscussionSubscriptions' => [],
			],
		],
		'permissions_check:comment' => [
			'object' => [
				'Elgg\Discussions\Permissions::preventCommentOnClosedDiscussion' => [],
			],
		],
		'prepare' => [
			'notification:create:object:comment' => [
				'Elgg\Discussions\Notifications::prepareCommentOnDiscussionNotification' => [],
			],
		],
		'register' => [
			'menu:entity' => [
				'Elgg\Discussions\Menus\Entity::registerStatusToggle' => [],
			],
			'menu:filter:filter' => [
				'Elgg\Discussions\Menus\Filter::filterTabsForDiscussions' => [],
			],
			'menu:owner_block' => [
				'Elgg\Discussions\Menus\OwnerBlock::registerGroupItem' => [],
			],
			'menu:site' => [
				'Elgg\Discussions\Menus\Site::register' => [],
			],
		],
		'seeds' => [
			'database' => [
				'Elgg\Discussions\Seeder::register' => [],
			],
		],
	],
	'group_tools' => [
		'forum' => [],
	],
	'notifications' => [
		'object' => [
			'discussion' => [
				'create' => [
					CreateDiscussionEventHandler::class => [],
				],
				'mentions' => [
					\Elgg\Notifications\Handlers\Mentions::class => [],
				],
			],
		],
	],
	'view_extensions' => [
		'forms/discussion/edit' => [
			'discussion/auto_close' => ['priority' => 400],
		],
		'groups/edit/settings' => [
			'discussion/groups/settings' => [],
		],
	],
	'widgets' => [
		'discussions' => [
			'context' => ['profile', 'dashboard'],
		],
	],
];
