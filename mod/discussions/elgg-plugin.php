<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'discussion',
			'class' => 'ElggDiscussion',
			'searchable' => true,
		],
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
		'collection:object:discussion:group' => [
			'path' => '/discussion/group/{guid}',
			'resource' => 'discussion/group',
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
	'upgrades' => [
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReply',
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReplyRiver',
	],
];
