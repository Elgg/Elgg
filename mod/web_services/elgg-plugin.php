<?php

use Elgg\Router\Middleware\AdminGatekeeper;

return [
	'settings' => [
		'auth_allow_key' => 1,
		'auth_allow_hmac' => 1,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'api_key',
			'class' => 'ElggApiKey',
		],
	],
	'actions' => [
		'webservices/api_key/edit' => [
			'access' => 'admin',
		],
		'webservices/api_key/regenerate' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'add:object:api_key' => [
			'path' => '/webservices/api_key/add',
			'resource' => 'webservices/api_key/add',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'edit:object:api_key' => [
			'path' => '/webservices/api_key/edit/{guid}',
			'resource' => 'webservices/api_key/edit',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'default:services' => [
			'path' => '/services/{segments}',
			'handler' => 'ws_page_handler',
			'defaults' => [
				'segments' => '',
			],
			'requirements' => [
				'segments' => '.+',
			],
			'walled' => false,
		],
	],
];
