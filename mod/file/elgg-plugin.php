<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'file',
			'searchable' => true,
		],
	],
	'actions' => [
		'file/upload' => [],
		'file/delete' => [],
	],
	'views' => [
		'default' => [
			'file/icons/' => __DIR__ . '/graphics/icons',
		],
	],
	'widgets' => [
		'filerepo' => [
			'name' => elgg_echo('file'),
			'description' => elgg_echo('file:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
