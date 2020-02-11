<?php

use Elgg\Router\Middleware\AdminGatekeeper;

/**
 * A global array holding API methods.
 * The structure of this is
 * 	$API_METHODS = array (
 * 		$method => array (
 * 			"description" => "Some human readable description"
 * 			"function" = 'my_function_callback'
 * 			"parameters" = array (
 * 				"variable" = array ( // the order should be the same as the function callback
 * 					type => 'int' | 'bool' | 'float' | 'string'
 * 					required => true (default) | false
 *					default => value // optional
 * 				)
 * 			)
 * 			"call_method" = 'GET' | 'POST'
 * 			"require_api_auth" => true | false (default)
 * 			"require_user_auth" => true | false (default)
 * 		)
 *  )
 */
global $API_METHODS;
$API_METHODS = [];

/** Define a global array of errors */
global $ERRORS;
$ERRORS = [];

require_once(__DIR__ . '/lib/functions.php');
require_once(__DIR__ . '/lib/web_services.php');
require_once(__DIR__ . '/lib/api_user.php');
require_once(__DIR__ . '/lib/client.php');
require_once(__DIR__ . '/lib/tokens.php');

return [
	'bootstrap' => \Elgg\WebServices\Bootstrap::class,
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
		'rest:output' => [
			'system.api.list' => [
				'ws_system_api_list_hook' => [],
			],
		],
	],
];
