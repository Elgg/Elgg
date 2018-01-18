<?php

return [
	'actions' => [
		'messageboard/add' => [],
		'messageboard/delete' => [],
	],
	'routes' => [
		'collection:annotation:messageboard:owner' => [
			'path' => '/messageboard/owner/{username}',
			'resource' => 'messageboard/owner',
		],
		'collection:annotation:messageboard:history' => [
			'path' => '/messageboard/owner/{username}/history/{history_username}',
			'resource' => 'messageboard/owner',
		],
	],
	'widgets' => [
		'messageboard' => [
			'name' => elgg_echo('messageboard:board'),
			'description' => elgg_echo('messageboard:desc'),
			'context' => ['profile'],
		],
	],
];
