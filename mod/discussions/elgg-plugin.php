<?php

use Elgg\Discussions\GroupToolContainerLogicCheck;

return [
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
				GroupToolContainerLogicCheck::class => [],
			],
		],
		'filter_tabs' => [
			'discussion' => [
				'\Elgg\Discussions\Menus::filterTabs' => [],
			],
		],
	],
	'upgrades' => [
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReply',
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReplyRiver',
	],
];
