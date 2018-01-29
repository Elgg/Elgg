<?php

return [
	'index' => [
		'path' => '/',
		'resource' => 'index',
	],
	'account:register' => [
		'path' => '/register',
		'resource' => 'account/register',
	],
	'account:login' => [
		'path' => '/login',
		'resource' => 'account/login',
	],
	'account:password:reset' => [
		'path' => '/forgotpassword',
		'resource' => 'account/forgotten_password',
	],
	'account:password:change' => [
		'path' => '/changepassword',
		'resource' => 'account/change_password',
	],
	'ajax' => [
		'path' => '/ajax/{segments}',
		'handler' => '_elgg_ajax_page_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'robots.txt' => [
		'path' => '/robots.txt',
		'resource' => 'robots.txt',
	],
	'favicon.ico' => [
		'path' => '/favicon.ico',
		'resource' => 'favicon.ico',
	],
	'manifest.json' => [
		'path' => '/manifest.json',
		'resource' => 'manifest.json',
	],
	'action' => [
		'path' => '/action/{segments}',
		'handler' => '_elgg_action_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'action:token' => [
		'path' => '/refresh_token',
		'handler' => '_elgg_csrf_token_refresh',
	],
	'admin' => [
		'path' => '/admin/{segments?}',
		'handler' => '_elgg_admin_page_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'admin_plugins_refresh' => [
		'path' => '/admin_plugins_refresh',
		'handler' => '_elgg_ajax_plugins_update',
	],
	'admin_plugin_text_file' => [
		'path' => '/admin_plugin_text_file/{plugin_id}/{filename}',
		'resource' => 'admin/plugin_text_file',
	],
	'phpinfo' => [
		'path' => '/phpinfo',
		'resource' => 'phpinfo',
	],
	'cron' => [
		'path' => '/cron/{segments}',
		'handler' => '_elgg_cron_page_handler',
		'requirements' => [
			'segments' => '.+',
		],
	],
	'serve-icon' => [
		'path' => '/serve-icon/{guid}/{size}',
		'handler' => '_elgg_filestore_serve_icon_handler',
		'requirements' => [
			'segments' => '.+',
		],
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
	],
	'settings:account' => [
		'path' => '/settings/user/{username?}',
		'resource' => 'settings/account',
	],
	'settings:statistics' => [
		'path' => '/settings/statistics/{username?}',
		'resource' => 'settings/statistics',
	],
	'settings:tools' => [
		'path' => '/settings/plugins/{username?}/{plugin_id}',
		'resource' => 'settings/tools',
	],
	'widgets:add_panel' => [
		'path' => '/widgets/add_panel',
		'resource' => 'widgets/add_panel',
	],
	'view:object:comment' => [
		'path' => '/comment/view/{guid}/{container_guid?}',
		'resource' => 'comments/view',
	],
	'edit:object:comment' => [
		'path' => '/comment/edit/{guid}',
		'resource' => 'comments/edit',
	],
	'view:user' => [
		'path' => '/user/{guid}',
		'resource' => 'user/view',
	],
	'edit:user:avatar' => [
		'path' => '/avatar/edit/{username}',
		'resource' => 'avatar/edit',
	],
];
