<?php

return [
	'actions' => [
		'river/delete' => [
			'access' => 'admin',
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
