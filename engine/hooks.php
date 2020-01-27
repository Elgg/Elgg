<?php
return [
	'access_collection:name' => [
		'access_collection' => [
			'access_friends_acl_get_name' => [],
		],
	],
	'action:validate' => [
		'all' => [
			\Elgg\Entity\CropIcon::class => [],
		],
	],
	'container_permissions_check' => [
		'all' => [
			'_elgg_groups_container_override' => [],
		],
	],
	'cron' => [
		'daily' => [
			'_elgg_session_cleanup_persistent_login' => [],
		],
	],
	'entity:url' => [
		'object' => [
			'_elgg_widgets_widget_urls' => [],
		],
	],
	'permissions_check:comment' => [
		'object' => [
			'_elgg_groups_comment_permissions_override' => ['priority' => 999],
		],
	],
	'register' => [
		'menu:annotation' => [
			'_elgg_annotations_default_menu_items' => [],
		],
		'menu:entity' => [
			'_elgg_upgrade_entity_menu' => ['priority' => 501],
		],
	],
	'search:fields' => [
		'group' => [
			\Elgg\Search\GroupSearchFieldsHandler::class => [],
			\Elgg\Search\TagsSearchFieldsHandler::class => [],
		],
		'object' => [
			\Elgg\Search\ObjectSearchFieldsHandler::class => [],
			\Elgg\Search\TagsSearchFieldsHandler::class => [],
		],
		'user' => [
			\Elgg\Search\UserSearchFieldsHandler::class => [],
			\Elgg\Search\TagsSearchFieldsHandler::class => [],
		],
	],
];
