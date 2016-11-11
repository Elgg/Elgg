<?php

return [
	'actions' => [
		'reportedcontent/add' => [],

		'reportedcontent/delete' => [
			'access' => 'admin',
		],
		'reportedcontent/archive' => [
			'access' => 'admin',
		],
	],
	'widgets' => [
		'reportedcontent' => [
			'description' => elgg_echo('reportedcontent:widget:description'),
			'context' => ['admin'],
		],
	],
];
