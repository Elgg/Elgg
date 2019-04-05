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
			'context' => ['profile'],
		],
	],
];
