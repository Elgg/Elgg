<?php

return [
	'plugin' => [
		'name' => 'Developer Tools',
	],
	'settings' => [
		'screen_log' => 0,
		'show_strings' => 0,
		'wrap_views' => 0,
		'log_events' => 0,
		'enable_error_log' => 0,
	],
	'bootstrap' => \Elgg\Developers\Bootstrap::class,
	'actions' => [
		'developers/entity_explorer_delete' => [
			'access' => 'admin',
		],
	],
	'events' => [
		'action:validate' => [
			'plugins/settings/save' => [
				'Elgg\Developers\PluginSettingsSaveHandler' => [],
			],
		],
		'register' => [
			'menu:admin_header' => [
				'Elgg\Developers\Menus\AdminHeader::register' => [],
			],
			'menu:entity' => [
				'Elgg\Developers\Menus\Entity::registerEntityExplorer' => [],
			],
			'menu:entity:trash' => [
				'Elgg\Developers\Menus\Entity::registerEntityExplorer' => [],
			],
			'menu:entity_explorer' => [
				'Elgg\Developers\Menus\EntityExplorer::register' => [],
				'Elgg\Menus\EntityTrash::registerRestore' => ['priority' => 400],
			],
		],
	],
];
