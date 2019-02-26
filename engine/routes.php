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
		],
	],
	'ajax' => [
		'path' => '/ajax/{segments}',
		'handler' => '_elgg_ajax_page_handler',
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
	'action:token' => [
		'path' => '/refresh_token',
		'controller' => \Elgg\Controllers\RefreshCsrfToken::class,
		'walled' => false,
		'middleware' => [
			\Elgg\Router\Middleware\AjaxGatekeeper::class,
		]
	],
	'admin' => [
		'path' => '/admin/{segments?}',
		'handler' => '_elgg_admin_page_handler',
		'requirements' => [
			'segments' => '.+',
		],
		'middleware' => [
			\Elgg\Router\Middleware\AdminGatekeeper::class,
		],
	],
	'admin_plugins_refresh' => [
		'path' => '/admin_plugins_refresh',
		'handler' => '_elgg_ajax_plugins_update',
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
		'handler' => '_elgg_cron_page_handler',
		'requirements' => [
			'segments' => '.+',
		],
		'walled' => false,
	],
	'serve-icon' => [
		'path' => '/serve-icon/{guid}/{size}',
		'handler' => '_elgg_filestore_serve_icon_handler',
		'requirements' => [
			'segments' => '.+',
		],
		'walled' => false,
	],
	'livesearch' => [
		'path' => '/livesearch/{match_on?}',
		'resource' => 'livesearch',
		'requirements' => [
			'match_on' => '\w+',
		],
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
	],
	'settings:statistics' => [
		'path' => '/settings/statistics/{username?}',
		'resource' => 'settings/statistics',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
	'settings:tools' => [
		'path' => '/settings/plugins/{username?}/{plugin_id}',
		'resource' => 'settings/tools',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
	'widgets:add_panel' => [
		'path' => '/widgets/add_panel',
		'resource' => 'widgets/add_panel',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
	'view:object:comment' => [
		'path' => '/comment/view/{guid}/{container_guid?}',
		'resource' => 'comments/view',
	],
	'edit:object:comment' => [
		'path' => '/comment/edit/{guid}',
		'resource' => 'comments/edit',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
	'view:user' => [
		'path' => '/user/{guid}',
		'resource' => 'user/view',
	],
	'edit:user:avatar' => [
		'path' => '/avatar/edit/{username}',
		'resource' => 'avatar/edit',
		'middleware' => [
			\Elgg\Router\Middleware\Gatekeeper::class,
		],
	],
];
