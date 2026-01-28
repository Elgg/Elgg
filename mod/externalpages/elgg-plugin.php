<?php

return [
	'plugin' => [
		'name' => 'Site Pages',
	],
	'bootstrap' => \Elgg\ExternalPages\Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'external_page',
			'class' => \ElggExternalPage::class,
		],
	],
	'actions' => [
		'external_page/edit' => [
			'access' => 'admin',
			'controller' => \Elgg\Controllers\EntityEditAction::class,
			'options' => [
				'entity_type' => 'object',
				'entity_subtype' => 'external_page',
			],
		],
	],
	'events' => [
		'form:prepare:fields' => [
			'external_page/edit' => [
				\Elgg\ExternalPages\Forms\PrepareFields::class => [],
			],
		],
		'register' => [
			'menu:admin_header' => [
				'Elgg\ExternalPages\Menus\AdminHeader::register' => [],
			],
			'menu:external_pages' => [
				'Elgg\ExternalPages\Menus\ExternalPages::register' => [],
			],
			'menu:footer' => [
				'Elgg\ExternalPages\Menus::register' => [],
			],
			'menu:walled_garden' => [
				'Elgg\ExternalPages\Menus::register' => [],
			],
		],
	],
	'upgrades' => [
		\Elgg\ExternalPages\Upgrades\MigrateEntities::class,
	],
];
