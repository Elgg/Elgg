<?php

$membership = __DIR__ . '/actions/groups/membership/';

return [
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
	'widgets' => [
		'a_users_groups' => [
			'name' => elgg_echo('groups:widget:membership'),
			'description' => elgg_echo('groups:widgets:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
	
