<?php

return [
	'actions' => [
		'diagnostics/download' => [
			'access' => 'admin',
		],
	],
	'hooks' => [
		'diagnostics:report' => [
			'system' => [
				'Elgg\Diagnostics\Reports::getBasic' => ['priority' => 0],
				'Elgg\Diagnostics\Reports::getSigs' => ['priority' => 1],
				'Elgg\Diagnostics\Reports::getGlobals' => [],
				'Elgg\Diagnostics\Reports::getPHPInfo' => [],
			],
		],
		'register' => [
			'menu:page' => [
				'Elgg\Diagnostics\Menus\Page::register' => [],
			]
		]
	],
];
