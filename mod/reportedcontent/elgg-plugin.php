<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'reported_content',
			'class' => 'ElggReportedContent',
			'searchable' => false,
		],
	],
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
			'context' => ['admin'],
		],
	],
];
