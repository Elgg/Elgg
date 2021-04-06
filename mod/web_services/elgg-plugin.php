<?php

use Elgg\Router\Middleware\AdminGatekeeper;
use Elgg\WebServices\Middleware\ApiContextMiddleware;
use Elgg\WebServices\Middleware\RestApiErrorHandlingMiddleware;
use Elgg\WebServices\Middleware\RestApiOutputMiddleware;
use Elgg\WebServices\Middleware\ViewtypeMiddleware;
use Elgg\WebServices\RestServiceController;

require_once(__DIR__ . '/lib/functions.php');
require_once(__DIR__ . '/lib/pam_handlers.php');
require_once(__DIR__ . '/lib/web_services.php');

return [
	'plugin' => [
		'name' => 'Web Services',
	],
	'bootstrap' => \Elgg\WebServices\Bootstrap::class,
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
	'hooks' => [
		'register' => [
			'menu:entity' => [
				'\Elgg\WebServices\EntityMenu' => [],
			],
			'menu:page' => [
				'\Elgg\WebServices\AdminPageMenu' => [],
			],
		],
	],
];
