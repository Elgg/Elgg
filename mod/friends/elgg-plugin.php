<?php

return [
	'actions' => [
		'friends/add' => [],
		'friends/remove' => [],
		'friends/collections/add' => [],
		'friends/collections/delete' => [],
		'friends/collections/edit' => [],
	],
	'widgets' => [
		'friends' => [
			'description' => elgg_echo('friends:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
	
