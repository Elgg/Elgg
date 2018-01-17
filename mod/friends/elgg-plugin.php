<?php

return [
	'actions' => [
		'friends/add' => [],
		'friends/remove' => [],
	],
	'routes' => [
		'collection:friends:owner' => [
			'path' => '/friends/{username}',
			'resource' => 'friends/index',
		],
		'collection:friends_of:owner' => [
			'path' => '/friendsof/{username}',
			'resource' => 'friends/of',
		],
	],
	'widgets' => [
		'friends' => [
			'description' => elgg_echo('friends:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
	
