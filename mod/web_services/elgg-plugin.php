<?php

use Elgg\Router\Middleware\AdminGatekeeper;
use Elgg\WebServices\ApiMethods\AuthGetToken;
use Elgg\WebServices\ApiMethods\SystemApiList;
use Elgg\WebServices\Forms\PrepareFields;
use Elgg\WebServices\Middleware\ApiContextMiddleware;
use Elgg\WebServices\Middleware\RestApiErrorHandlingMiddleware;
use Elgg\WebServices\Middleware\RestApiOutputMiddleware;
use Elgg\WebServices\Middleware\ViewtypeMiddleware;
use Elgg\WebServices\RestServiceController;

require_once(__DIR__ . '/lib/functions.php');
require_once(__DIR__ . '/lib/web_services.php');

return [
	'plugin' => [
		'name' => 'Web Services',
	],
	'settings' => [
		'auth_allow_key' => 1,
		'auth_allow_hmac' => 1,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'api_key',
			'class' => 'ElggApiKey',
			'capabilities' => [
				'commentable' => false,
			],
		],
	],
	'actions' => [
		'webservices/api_key/edit' => [
			'access' => 'admin',
		],
		'webservices/api_key/regenerate' => [
			'access' => 'admin',
		],
		'webservices/api_key/toggle_active' => [
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
		'default:services:rest' => [
			'path' => '/services/api/rest/{view}/{segments?}',
			'controller' => RestServiceController::class,
			'defaults' => [
				'view' => 'json',
			],
			'middleware' => [
				ApiContextMiddleware::class,
				ViewtypeMiddleware::class,
				RestApiOutputMiddleware::class,
				RestApiErrorHandlingMiddleware::class,
			],
			'requirements' => [
				'segments' => '.+',
			],
			'walled' => false,
		],
	],
	'view_extensions' => [
		'admin.css'	=> [
			'webservices/admin.css' => [],
		],
	],
	'events' => [
		'form:prepare:fields' => [
			'webservices/api_key/edit' => [
				PrepareFields::class => [],
			],
		],
		'register' => [
			'menu:admin_header' => [
				'\Elgg\WebServices\AdminHeaderMenu' => [],
			],
			'menu:entity' => [
				'\Elgg\WebServices\EntityMenu' => [],
			],
		],
	],
	'web_services' => [
		'auth.gettoken' => [
			'POST' => [
				'callback' => AuthGetToken::class,
				'params' => [
					'username' => ['type' => 'string'],
					'password' => ['type' => 'string'],
				],
			],
		],
		'system.api.list' => [
			'GET' => [
				'callback' => SystemApiList::class,
			],
		],
	],
];
