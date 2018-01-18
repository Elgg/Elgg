<?php

return [
	'actions' => [
		'river/delete' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'collection:river:owner' => [
			'path' => '/activity/owner/{username}',
			'resource' => 'river',
		],
		'collection:river:friends' => [
			'path' => '/activity/friends',
			'resource' => 'river',
		],
		'collection:river:group' => [
			'path' => '/activity/group/{guid}',
			'resource' => 'activity/group',
		],
		'collection:river:all' => [
			'path' => '/activity/all',
			'resource' => 'river',
		],
		'default:river' => [
			'path' => '/activity',
			'resource' => 'river',
		],
	],
	'widgets' => [
		'group_activity' => [
			'name' => elgg_echo('activity:widgets:group_activity:title'),
			'description' => elgg_echo('activity:widgets:group_activity:description'),
			'context' => ['dashboard'],
			'multiple' => true,
		],
		'river_widget' => [
			'name' => elgg_echo('activity:widgets:river_widget:title'),
			'description' => elgg_echo('activity:widgets:river_widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
