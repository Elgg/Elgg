<?php

return [
	'plugin' => [
		'name' => 'Profile',
		'activate_on_install' => true,
	],
	'actions' => [
		'profile/edit' => [],
		'profile/edit/header' => [],
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
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'edit:user:header' => [
			'path' => '/profile/{username}/edit_header',
			'resource' => 'profile/edit_header',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
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
			'menu:admin_header' => [
				'Elgg\Profile\Menus\AdminHeader::register' => [],
				'Elgg\Profile\Menus\AdminHeader::registerAdminProfileFields' => [],
			],
			'menu:filter:profile/edit' => [
				'Elgg\Profile\Menus\Filter::registerProfileEdit' => [],
			],
			'menu:profile_admin' => [
				'Elgg\Profile\Menus\ProfileAdmin::registerUserHover' => [],
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
