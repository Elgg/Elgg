<?php

return [
	'plugin' => [
		'name' => 'Reported Content',
		'activate_on_install' => true,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'reported_content',
			'class' => 'ElggReportedContent',
			'capabilities' => [
				'commentable' => false,
			],
		],
	],
	'actions' => [
		'reportedcontent/add' => [],
		'reportedcontent/archive' => [
			'access' => 'admin',
		],
	],
	'widgets' => [
		'reportedcontent' => [
			'context' => ['admin'],
		],
	],
	'view_options' => [
		'forms/reportedcontent/add' => ['ajax' => true],
	],
	'hooks' => [
		'register' => [
			'menu:entity:object:reported_content' => [
				'Elgg\ReportedContent\Menus\Entity::registerArchive' => [],
			],
			'menu:entity' => [
				'Elgg\ReportedContent\Menus\Entity::registerEntityReporting' => [],
			],
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
	'notifications' => [
		'object' => [
			'reported_content' => [
				'create' => \Elgg\ReportedContent\Notifications\CreateReportedContentNotificationHandler::class,
			],
		],
	],
	'view_extensions' => [
		'notifications/settings/records' => [
			'reportedcontent/notifications/settings' => [],
		],
	],
];
