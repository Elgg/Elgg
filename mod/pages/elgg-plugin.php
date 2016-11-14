<?php

return [
	'actions' => [
		'pages/edit' => [],
		'pages/delete' => [],
		'annotations/page/delete' => [],
	],
	'views' => [
		'default' => [
			'pages/' => __DIR__ . '/images',
		],
	],
	'widgets' => [
		'pages' => [
			'description' => elgg_echo('pages:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
