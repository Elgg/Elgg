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
	'view_extensions' => [
		'admin.css' => [
			'reportedcontent/admin_css' => [],
		],
	],
	'view_options' => [
		'forms/reportedcontent/add' => ['ajax' => true],
	],
	'hooks' => [
		'register' => [
			'menu:footer' => [
				'Elgg\ReportedContent\Menus\Footer::register' => [],
			],
			'menu:page' => [
				'Elgg\ReportedContent\Menus\Page::register' => [],
			],
			'menu:user_hover' => [
				'Elgg\ReportedContent\Menus\UserHover::register' => [],
			],
		],
	],
];
