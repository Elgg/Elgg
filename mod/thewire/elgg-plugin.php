<?php

return [
	'settings' => [
		'limit' => 140,
	],
	'actions' => [
		'thewire/add' => [],
		'thewire/delete' => [],
	],
	'widgets' => [
		'thewire' => [
			'description' => elgg_echo('thewire:widget:desc'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
