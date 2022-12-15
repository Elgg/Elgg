<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Likes',
		'activate_on_install' => true,
	],
	'actions' => [
		'likes/add' => [],
		'likes/delete' => [],
	],
	'view_options' => [
		'likes/popup' => ['ajax' => true],
	],
	'view_extensions' => [
		'elgg.css' => [
			'elgg/likes.css' => [],
		],
	],
	'events' => [
		'ajax_response' => [
			'all' => [
				Elgg\Likes\AjaxResponseHandler::class => [],
			],
		],
		'delete' => [
			'group' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
			'object' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
			'site' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
			'user' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
		],
		'elgg.data' => [
			'site' => [
				Elgg\Likes\JsConfigHandler::class => [],
			],
		],
		'permissions_check' => [
			'annotation' => [
				'Elgg\Likes\Permissions::allowLikedEntityOwner' => [],
			],
		],
		'permissions_check:annotate' => [
			'all' => [
				'Elgg\Likes\Permissions::allowLikeOnEntity' => ['priority' => 0],
			],
		],
		'prepare' => [
			'system:email' => [
				Elgg\Likes\ThreadHeadersHandler::class => [],
			],
		],
		'register' => [
			'menu:social' => [
				'Elgg\Likes\Menus\Social::register' => [],
			],
		],
		'view_vars' => [
			'page/components/list' => [
				'Elgg\Likes\Preloader::preload' => [],
			],
		],
	],
];
