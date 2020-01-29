<?php

use ElggPlugin\Profile\AnnotationMigration;

return [
	'upgrades' => [
		AnnotationMigration::class,
	],
	'actions' => [
		'profile/edit' => [],
		'profile/fields/reset' => [
			'access' => 'admin',
		],
		'profile/fields/add' => [
			'access' => 'admin',
		],
		'profile/fields/delete' => [
			'access' => 'admin',
		],
		'profile/fields/reorder' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'view:user' => [
			'path' => '/profile/{username?}',
			'resource' => 'profile/view',
		],
		'edit:user' => [
			'path' => '/profile/{username}/edit',
			'resource' => 'profile/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'profile/profile.css' => [],
		],
	],
	'view_options' => [
		'forms/profile/fields/add' => ['ajax' => true],
	],
	'hooks' => [
		'get_views' => [
			'ecml' => [
				'Elgg\Profile\ECML::getViews' => [],
			],
		],
		'get_list' => [
			'default_widgets' => [
				'Elgg\Profile\Widgets::getDefaultWidgetsList' => [],
			],
		],
		'init' => [
			'system' => [
				'Elgg\Profile\ProfileFields::setup' => ['priority' => 10000],
			],
		],
		'register' => [
			'menu:page' => [
				'Elgg\Profile\Menus\Page::registerAdminProfileFields' => [],
				'Elgg\Profile\Menus\Page::registerProfileEdit' => [],
			],
			'menu:title' => [
				'Elgg\Profile\Menus\Title::register' => [],
			],
			'menu:topbar' => [
				'Elgg\Profile\Menus\Topbar::register' => [],
			],
			'menu:user_hover' => [
				'Elgg\Profile\Menus\UserHover::register' => [],
			],
		],
		'search:fields' => [
			'user' => [
				\Elgg\Search\UserSearchProfileFieldsHandler::class => [],
			],
		],
	],
];
