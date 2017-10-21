<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'file',
			'class' => '\ElggFile',
			'searchable' => true,
		],
	],
	'actions' => [
		'file/upload' => [],
		'file/delete' => [],
	],
	'widgets' => [
		'filerepo' => [
			'name' => elgg_echo('file'),
			'description' => elgg_echo('file:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
