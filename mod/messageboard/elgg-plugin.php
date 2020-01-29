<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'actions' => [
		'messageboard/add' => [],
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
