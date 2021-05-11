<?php

return [
	'plugin' => [
		'name' => 'Profile',
		'activate_on_install' => true,
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
	'events' => [
		'create' => [
			'user' => [
				\Elgg\Widgets\CreateDefaultWidgetsHandler::class => [],
			],
		],
	],
	'hooks' => [
		'fields' => [
			'user:user' => [
				\Elgg\Profile\FieldsHandler::class => [],
			],
		],
		'get_list' => [
			'default_widgets' => [
				'Elgg\Profile\Widgets::getDefaultWidgetsList' => [],
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
