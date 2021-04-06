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
		'page/components/list' => [
			'likes/before_lists' => ['priority' => 1],
		],
	],
	'hooks' => [
		'ajax_response' => [
			'all' => [
				Elgg\Likes\AjaxResponseHandler::class => [],
			],
		],
		'elgg.data' => [
			'site' => [
				Elgg\Likes\JsConfigHandler::class => [],
			],
		],
		'permissions_check' => [
			'annotation' => [
				'Elgg\Likes\Permissions::allowLikedEntityOwner' =>[],
			],
		],
		'permissions_check:annotate' => [
			'all' => [
				'Elgg\Likes\Permissions::allowLikeOnEntity' => ['priority' => 0],
			],
		],
		'register' => [
			'menu:social' => [
				'Elgg\Likes\Menus\Social::register' => [],
			],
		],
	],
	'events' => [
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
	],
];
