<?php

return [
	'actions' => [
		'messageboard/add' => [],
		'messageboard/delete' => [],
	],
	'widgets' => [
		'messageboard' => [
			'title' => elgg_echo('messageboard:board'),
			'description' => elgg_echo('messageboard:desc'),
			'context' => ['profile'],
		],
	],
];
