<?php

return [
	'actions' => [
		'friends/add' => [],
		'friends/remove' => [],
	],
	'widgets' => [
		'friends' => [
			'description' => elgg_echo('friends:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
	
