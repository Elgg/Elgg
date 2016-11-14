<?php

return [
	'actions' => [
		'river/delete' => [
			'access' => 'admin',
		],
	],
	'widgets' => [
		'group_activity' => [
			'title' => elgg_echo('groups:widget:group_activity:title'),
			'description' => elgg_echo('groups:widget:group_activity:description'),
			'context' => ['dashboard'],
			'multiple' => true,
		],
	],
];
