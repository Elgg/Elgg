<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'page',
			'searchable' => true,
			'class' => '\ElggPage',
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
	'upgrades' => [
		'\Elgg\Pages\Upgrades\MigratePageTop',
	],
];
