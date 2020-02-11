<?php

use Elgg\Discussions\GroupToolContainerLogicCheck;

require_once(__DIR__ . '/lib/functions.php');

return [
	'bootstrap' => \Elgg\Discussions\Bootstrap::class,
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
		'filter_tabs' => [
			'discussion' => [
				'Elgg\Discussions\Menus\Filter::filterTabsForDiscussions' => [],
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
			'notification:create:object:discussion' => [
				'Elgg\Discussions\Notifications::prepareDiscussionCreateNotification' => [],
			],
		],
		'register' => [
			'menu:filter:groups/all' => [
				'Elgg\Discussions\Menus\Filter::registerGroupsAll' => [],
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
				'Elgg\Discussions\Database::registerSeeds' => [],
			],
		],
	],
	'upgrades' => [
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReply',
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReplyRiver',
	],
	'group_tools' => [
		'forum' => [],
	],
];
