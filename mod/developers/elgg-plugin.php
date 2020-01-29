<?php

return [
	'actions' => [
		'developers/settings' => [
			'access' => 'admin',
		],
		'developers/ajax_demo' => [
			'access' => 'admin',
		],
		'developers/entity_explorer_delete' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'default:theme_sandbox' => [
			'path' => '/theme_sandbox/{page?}',
			'resource' => 'theme_sandbox',
			'defaults' => [
				'page' => 'intro',
			],
		],
		'default:developers:ajax_demo' => [
			'path' => '/developers_ajax_demo',
			'resource' => 'developers/ajax_demo',
		],
	],
	
	'view_options' => [
		'developers/ajax' => ['ajax' => true],
		'developers/ajax_demo.html' => ['ajax' => true],
		'forms/developers/ajax_demo' => ['ajax' => true],
		'theme_sandbox/components/tabs/ajax' => ['ajax' => true],
	],
];
