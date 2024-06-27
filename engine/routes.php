<?php

return [
	'index' => [
		'path' => '/',
		'resource' => 'index',
		'walled' => false,
	],
	'upgrade:init' => [
		'path' => '/upgrade/init',
		'resource' => 'upgrade',
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\UpgradeGatekeeper::class,
			\Elgg\Router\Middleware\RewriteTest::class,
		],
	],
	'upgrade:run' => [
		'path' => '/upgrade/run',
		'controller' => \Elgg\Upgrade\UpgradeController::class,
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\UpgradeGatekeeper::class,
		],
	],
	'account:register' => [
		'path' => '/register',
		'resource' => 'account/register',
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
			\Elgg\Router\Middleware\RegistrationAllowedGatekeeper::class,
		],
	],
	'account:login' => [
		'path' => '/login',
		'resource' => 'account/login',
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
		],
	],
	'account:password:reset' => [
		'path' => '/forgotpassword',
		'resource' => 'account/forgotten_password',
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
		],
	],
	'account:password:change' => [
		'path' => '/changepassword',
		'resource' => 'account/change_password',
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
			\Elgg\Router\Middleware\SignedRequestGatekeeper::class,
		],
	],
	'account:email:confirm' => [
		'path' => '/emailconfirm/{guid}',
		'controller' => \Elgg\Users\EmailChangeController::class,
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\SignedRequestGatekeeper::class,
		],
	],
	'account:validation:pending' => [
		'path' => '/validation_pending',
		'resource' => 'account/validation_pending',
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
		],
	],
	'ajax' => [
		'path' => '/ajax/{segments}',
		'controller' => \Elgg\Ajax\Controller::class,
		'requirements' => [
			'segments' => '.+',
		],
		'middleware' => [
			\Elgg\Router\Middleware\AjaxGatekeeper::class,
		]
	],
	'robots.txt' => [
		'path' => '/robots.txt',
		'resource' => 'robots.txt',
		'walled' => false,
	],
	'favicon.ico' => [
		'path' => '/favicon.ico',
		'resource' => 'favicon.ico',
		'walled' => false,
	],
	'manifest.json' => [
		'path' => '/manifest.json',
		'resource' => 'manifest.json',
		'walled' => false,
	],
	'admin:plugin_settings' => [
		// needs to be registered before global admin route
		'path' => '/admin/plugin_settings/{plugin_id}',
		'resource' => 'admin/plugin_settings',
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'admin:online_users_count' => [
		// needs to be registered before global admin route
		'path' => '/admin/online_users_count',
		'controller' => \Elgg\Controllers\OnlineUsersCount::class,
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'admin' => [
		'path' => '/admin/{segments?}',
		'resource' => 'admin',
		'requirements' => [
			'segments' => '.+',
		],
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'admin_plugin_text_file' => [
		'path' => '/admin_plugin_text_file/{plugin_id}/{filename}',
		'resource' => 'admin/plugin_text_file',
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'phpinfo' => [
		'path' => '/phpinfo',
		'resource' => 'phpinfo',
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'cron' => [
		'path' => '/cron/{segments}',
		'controller' => \Elgg\Controllers\Cron::class,
		'requirements' => [
			'segments' => '.+',
		],
		'walled' => false,
	],
	'serve-icon' => [
		'path' => '/serve-icon/{guid}/{size}',
		'controller' => \Elgg\Controllers\ServeIcon::class,
		'walled' => false,
	],
	'livesearch' => [
		'path' => '/livesearch/{match_on?}',
		'resource' => 'livesearch',
		'requirements' => [
			'match_on' => '\w+',
		],
	],
	'security.txt' => [
		'path' => '/security.txt',
		'controller' => \Elgg\Controllers\SecurityTxt::class,
		'walled' => false,
	],
	'settings:index' => [
		'path' => '/settings',
		'resource' => 'settings/account',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
	'settings:account' => [
		'path' => '/settings/user/{username?}',
		'resource' => 'settings/account',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
		'detect_page_owner' => true,
	],
	'settings:notifications' => [
		'path' => '/settings/notifications/{username}',
		'resource' => 'settings/notifications',
		'middleware' => [
			\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
		],
		'detect_page_owner' => true,
	],
	'settings:notifications:users' => [
		'path' => '/settings/notifications/users/{username}',
		'resource' => 'settings/notifications/users',
		'middleware' => [
			\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
		],
		'detect_page_owner' => true,
	],
	'settings:statistics' => [
		'path' => '/settings/statistics/{username?}',
		'resource' => 'settings/statistics',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
		'detect_page_owner' => true,
	],
	'settings:tools' => [
		'path' => '/settings/plugins/{username}/{plugin_id}',
		'resource' => 'settings/tools',
		'middleware' => [
			\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
		],
		'detect_page_owner' => true,
	],
	'widgets:add_panel' => [
		// @todo this route could also be a ajax view or have some parameters (context/container) in the route definition
		'path' => '/widgets/add_panel',
		'resource' => 'widgets/add_panel',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
	'view:object:comment' => [
		'path' => '/comment/view/{guid}/{container_guid?}',
		'controller' => \Elgg\Controllers\CommentEntityRedirector::class,
	],
	'view:user' => [
		'path' => '/user/{guid}',
		'resource' => 'user/view',
	],
	'delete:user' => [
		'path' => '/user/delete/{guid}',
		'resource' => 'user/delete',
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'edit:user:avatar' => [
		'path' => '/avatar/edit/{username}',
		'resource' => 'avatar/edit',
		'middleware' => [
			\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
		],
	],
	'notifications:mute' => [
		'path' => 'notifications/mute/{entity_guid}/{recipient_guid}',
		'resource' => 'notifications/mute',
		'requirements' => [
			'entity_guid' => '\d+',
			'recipient_guid' => '\d+',
		],
		'middleware' => [
			\Elgg\Router\Middleware\SignedRequestGatekeeper::class,
		],
		'walled' => false,
	],
	'trash:owner' => [
		'path' => '/settings/trash/{username}',
		'resource' => 'trash/owner',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
			\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
		],
	],
	'trash:group' => [
		'path' => '/trash/group/{guid}',
		'resource' => 'trash/group',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
			\Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper::class,
		],
	],
];
