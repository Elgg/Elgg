<?php

use Elgg\Discussions\GroupToolContainerLogicCheck;
use Elgg\Discussions\Notifications\CreateDiscussionEventHandler;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Discussions',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'discussion',
			'class' => 'ElggDiscussion',
			'searchable' => true,
		],
	],
	'settings' => [
		'enable_global_discussions' => 0,
	],
	'actions' => [
		'discussion/save' => [],
		'discussion/toggle_status' => [],
	],
	'routes' => [
		'default:object:discussion' => [
			'path' => '/discussion',
			'resource' => 'discussion/all',
		],
		'collection:object:discussion:all' => [
			'path' => '/discussion/all',
			'resource' => 'discussion/all',
		],
		'collection:object:discussion:owner' => [
			'path' => '/discussion/owner/{username}',
			'resource' => 'discussion/owner',
		],
		'collection:object:discussion:my_groups' => [
			'path' => '/discussion/my_groups/{username}',
			'resource' => 'discussion/my_groups',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'collection:object:discussion:group' => [
			'path' => '/discussion/group/{guid}',
			'resource' => 'discussion/group',
			'required_plugins' => [
				'groups',
			],
		],
		'add:object:discussion' => [
			'path' => '/discussion/add/{guid}',
			'resource' => 'discussion/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
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
	'hooks' => [
		'container_logic_check' => [
			'object' => [
				'Elgg\Discussions\Permissions::containerLogic' => [],
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'get' => [
			'subscriptions' => [
				'Elgg\Discussions\Notifications::addGroupSubscribersToCommentOnDiscussionSubscriptions' => [],
			],
		],
		'likes:is_likable' => [
			'object:discussion' => [
				'Elgg\Values::getTrue' => [],
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
			'menu:title:object:discussion' => [
				\Elgg\Notifications\RegisterSubscriptionMenuItemsHandler::class => [],
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
				'create' => CreateDiscussionEventHandler::class,
			],
		],
	],
];
