<?php
return [
	'all' => [
		'all' => [
			'_elgg_enqueue_notification_event' => [
				'priority' => 700,
			],
		],
	],
	'ban' => [
		'user' => [
			'_elgg_user_ban_notification' => [],
		],
	],
	'cache:clear' => [
		'system' => [
			'_elgg_clear_caches' => [],
			'_elgg_reset_opcache' => [],
		],
	],
	'cache:clear:after' => [
		'system' => [
			'_elgg_enable_caches' => [],
			'_elgg_rebuild_public_container' => [],
		],
	],
	'cache:clear:before' => [
		'system' => [
			'_elgg_disable_caches' => [],
		],
	],
	'cache:invalidate' => [
		'system' => [
			'_elgg_invalidate_caches' => [],
		],
	],
	'cache:purge' => [
		'system' => [
			'_elgg_purge_caches' => [],
		],
	],
	'complete' => [
		'upgrade' => [
			'_elgg_upgrade_completed' => [],
		],
	],
	'create' => [
		'object' => [
			'_elgg_create_notice_of_pending_upgrade' => [],
		],
		'relationship' => [
			'access_friends_acl_add_friend' => [],
		],
		'user' => [
			'access_friends_acl_create' => [],
		],
	],
	'created' => [
		'river' => [
			'_elgg_river_update_object_last_action' => [],
		],
	],
	'delete' => [
		'relationship' => [
			'access_friends_acl_remove_friend' => [],
		],
	],
	'init' => [
		'system' => [
			'_elgg_admin_init' => [],
			'_elgg_init' => [],
			'_elgg_notifications_init' => [],
			'_elgg_views_init' => [],
			'_elgg_walled_garden_init' => [
				'priority' => 1000,
			],
			'users_init' => [
				'priority' => 0,
			],
		],
	],
	'login:before' => [
		'user' => [
			'_elgg_admin_user_validation_login_attempt' => [
				'priority' => 999, // allow others to throw exceptions earlier
			],
		],
	],
	'make_admin' => [
		'user' => [
			'_elgg_add_admin_widgets' => [],
		],
	],
	'ready' => [
		'system' => [
			'_elgg_cache_init' => [],
			'_elgg_default_widgets_init' => [],
			'access_init' => [],
		],
	],
	'update:after' => [
		'all' => [
			\Elgg\Comments\SyncContainerAccessHandler::class => [
				'priority' => 600,
			],
		],
		'group' => [
			'_elgg_filestore_move_icons' => [],
			'_elgg_filestore_touch_icons' => [],
		],
		'object' => [
			'_elgg_filestore_move_icons' => [],
			'_elgg_filestore_touch_icons' => [],
		],
	],
	'validate:after' => [
		'user' => [
			'_elgg_admin_user_validation_notification' => [],
		],
	],
];
