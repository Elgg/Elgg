<?php

$membership = __DIR__ . '/actions/groups/membership/';

return [
	'entities' => [
		[
			'type' => 'group',
			'subtype' => 'group',
			'searchable' => true,
		],
	],
	'upgrades' => [
		\Elgg\Groups\Upgrades\GroupIconTransfer::class,
	],
	'actions' => [
		'groups/edit' => [],
		'groups/featured' => [
			'access' => 'admin',
		],
		
		// membership actions
		'groups/invite' => [
			'filename' => "{$membership}invite.php",
		],
		'groups/join' => [
			'filename' => "{$membership}join.php",
		],
		'groups/leave' => [
			'filename' => "{$membership}leave.php",
		],
		'groups/remove' => [
			'filename' => "{$membership}remove.php",
		],
		'groups/killrequest' => [
			'filename' => "{$membership}delete_request.php",
		],
		'groups/killinvitation' => [
			'filename' => "{$membership}delete_invite.php",
		],
		'groups/addtogroup' => [
			'filename' => "{$membership}add.php",
		],
	],
	'routes' => [
		'default:group:group' => [
			'path' => '/groups',
			'resource' => 'groups/all',
		],
		'collection:group:group:all' => [
			'path' => '/groups/all',
			'resource' => 'groups/all',
		],
		'collection:group:group:owner' => [
			'path' => '/groups/owner/{username}',
			'resource' => 'groups/owner',
		],
		'collection:group:group:member' => [
			'path' => '/groups/member/{username}',
			'resource' => 'groups/member',
		],
		'collection:group:group:invitations' => [
			'path' => '/groups/invitations/{username}',
			'resource' => 'groups/invitations',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:group:group:search' => [
			'path' => '/groups/search',
			'resource' => 'groups/search',
		],
		'collection:user:user:group_members' => [
			'path' => '/groups/members/{guid}/{sort?}',
			'resource' => 'groups/members',
			'default' => [
				'sort' => 'alpha',
			],
		],
		'add:group:group' => [
			'path' => '/groups/add/{container_guid}',
			'resource' => 'groups/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'view:group:group' => [
			'path' => '/groups/profile/{guid}/{title?}',
			'resource' => 'groups/profile',
		],
		'edit:group:group' => [
			'path' => '/groups/edit/{guid}',
			'resource' => 'groups/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'invite:group:group' => [
			'path' => '/groups/invite/{guid}',
			'resource' => 'groups/invite',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'requests:group:group' => [
			'path' => '/groups/requests/{guid}',
			'resource' => 'groups/requests',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'widgets' => [
		'a_users_groups' => [
			'context' => ['profile', 'dashboard'],
		],
	],
];
	
