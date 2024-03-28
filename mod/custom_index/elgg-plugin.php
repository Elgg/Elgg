<?php

return [
	'plugin' => [
		'name' => 'Front Page Demo',
		'activate_on_install' => true,
		'dependencies' => [
			'activity' => [
				'must_be_active' => false,
				'position' => 'after',
			],
		],
	],
	'settings' => [
		'module_about_enabled' => 1,
		'module_register_enabled' => 1,
		'module_login_enabled' => 0,
		'module_activity_enabled' => 1,
		'module_blog_enabled' => 1,
		'module_bookmarks_enabled' => 1,
		'module_file_enabled' => 1,
		'module_groups_enabled' => 1,
		'module_users_enabled' => 1,
	],
	'view_extensions' => [
		'elgg.css' => [
			'custom_index/content.css' => [],
		],
	],
];
