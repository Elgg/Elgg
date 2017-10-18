<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'page',
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'page_top',
		],
	],
	'actions' => [
		'pages/edit' => [],
		'pages/delete' => [],
		'annotations/page/delete' => [],
	],
	'widgets' => [
		'pages' => [
			'description' => elgg_echo('pages:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
