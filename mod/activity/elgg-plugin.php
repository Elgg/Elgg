<?php

return [
	'plugin' => [
		'name' => 'Activity Pages',
		'activate_on_install' => true,
	],
	'routes' => [
		'collection:river:owner' => [
			'path' => '/activity/owner/{username}',
			'resource' => 'river',
		],
		'collection:river:friends' => [
			'path' => '/activity/friends/{username?}',
			'resource' => 'river',
			'required_plugins' => [
				'friends',
			],
		],
		'collection:river:group' => [
			'path' => '/activity/group/{guid}',
			'resource' => 'activity/group',
			'required_plugins' => [
				'groups',
			],
		],
		'collection:river:all' => [
			'path' => '/activity/all',
			'resource' => 'river',
		],
		'default:river' => [
			'path' => '/activity',
			'resource' => 'river',
		],
	],
	'widgets' => [
		'group_activity' => [
			'context' => ['dashboard'],
			'multiple' => true,
			'required_plugin' => 'groups',
		],
		'river_widget' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'group_tools' => [
		'activity' => [],
	],
	'view_extensions' => [
		'css/elgg' => [
			'river/filter.css' => [],
		],
	],
	'hooks' => [
		'register' => [
			'menu:site' => [
				'Elgg\Activity\Menus\Site::register' => [],
			],
			'menu:owner_block' => [
				'Elgg\Activity\Menus\OwnerBlock::registerUserItem' => [],
				'Elgg\Activity\Menus\OwnerBlock::registerGroupItem' => [],
			],
		],
	],
];
