<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Embed',
		'activate_on_install' => true,
	],
	'routes' => [
		'default:embed' => [
			'path' => '/embed/{tab?}',
			'resource' => 'embed/embed',
			'requirements' => [
				'tab' => '\w+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\AjaxGatekeeper::class,
			],
		],
	],
	'hooks' => [
		'entity:icon:url' => [
			'object' => [
				'Elgg\Embed\Icons::setThumbnailUrl' => ['priority' => 1000],
			],
		],
		'register' => [
			'menu:embed' => [
				'Elgg\Embed\Menus\Embed::selectCorrectTab' => ['priority' => 1000],
			],
			'menu:longtext' => [
				'Elgg\Embed\Menus\LongText::register' => [],
			],
		],
	],
	'view_extensions' => [
		'admin.css' => [
			'embed/css' => [],
		],
		'elgg.css' => [
			'embed/css' => [],
		],
	],
];
