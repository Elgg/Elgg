<?php

return [
	'index' => [
		'path' => '/',
		'resource' => 'index',
	],
	'ajax' => [
		'path' => '/ajax/{segments}',
		'handler' => '_elgg_ajax_page_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'favicon.ico' => [
		'path' => '/favicon.ico',
		'resource' => 'favicon.ico',
	],
	'manifest.json' => [
		'path' => '/manifest.json',
		'resource' => 'manifest.json',
	],
	'action' => [
		'path' => '/action/{segments}',
		'handler' => '_elgg_action_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'action' => [
		'path' => '/action/{segments}',
		'handler' => '_elgg_action_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'action:token' => [
		'path' => '/refresh_token',
		'handler' => '_elgg_csrf_token_refresh',
	],
	'livesearch' => [
		'path' => '/livesearch/{match_on?}',
		'resource' => 'livesearch',
		'requirements' => [
			'match_on' => '\w+',
		],
	],
	'settings:index' => [
		'path' => '/settings',
		'resource' => 'settings/account',
	],
	'settings:account' => [
		'path' => '/settings/user/{username?}',
		'resource' => 'settings/account',
	],
	'settings:statistics' => [
		'path' => '/settings/statistics/{username?}',
		'resource' => 'settings/statistics',
	],
	'settings:tools' => [
		'path' => '/settings/tools/{username?}/{plugin_id}',
		'resource' => 'settings/tools',
	],
];