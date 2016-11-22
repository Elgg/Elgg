<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'thewire',
			'class' => 'ElggWire',
			'searchable' => true,
		],
	],
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
