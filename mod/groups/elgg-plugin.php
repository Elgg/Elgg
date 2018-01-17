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
	'settings' => [
		'allow_activity' => 'yes',
	],
	'actions' => [
		'groups/edit' => [],
		'groups/delete' => [],
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
		],
		'collection:group:group:search' => [
			'path' => '/groups/search',
			'resource' => 'groups/search',
		],
		'collection:river:item:group' => [
			'path' => '/groups/activity/{guid}',
			'resource' => 'groups/activity',
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
		],
		'view:group:group' => [
			'path' => '/groups/profile/{guid}/{title?}',
			'resource' => 'groups/profile',
		],
		'edit:group:group' => [
			'path' => '/groups/edit/{guid}',
			'resource' => 'groups/edit',
		],
		'invite:group:group' => [
			'path' => '/groups/invite/{guid}',
			'resource' => 'groups/invite',
		],
		'requests:group:group' => [
			'path' => '/groups/requests/{guid}',
			'resource' => 'groups/requests',
		],
	],
	'widgets' => [
		'a_users_groups' => [
			'name' => elgg_echo('groups:widget:membership'),
			'description' => elgg_echo('groups:widgets:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
	
