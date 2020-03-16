<?php
return [
	'all' => [
		'all' => [
			\Elgg\Notifications\EnqueueEventHandler::class => [
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
			\Elgg\Upgrade\UpgradeCompletedAdminNoticeHandler::class => [],
		],
	],
	'create' => [
		'object' => [
			\Elgg\Upgrade\CreateAdminNoticeHandler::class => [],
		],
		'relationship' => [
			\Elgg\Friends\AddToAclHandler::class => [],
		],
		'user' => [
			\Elgg\Friends\CreateAclHandler::class => [],
		],
	],
	'created' => [
		'river' => [
			\Elgg\River\UpdateLastActionHandler::class => [],
		],
	],
	'delete' => [
		'relationship' => [
			\Elgg\Friends\RemoveFromAclHandler::class => [],
		],
	],
	'init' => [
		'system' => [
			'_elgg_init' => [],
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
			'Elgg\Users\Validation::preventUserLogin' => [
				'priority' => 999, // allow others to throw exceptions earlier
			],
		],
	],
	'make_admin' => [
		'user' => [
			\Elgg\Widgets\CreateAdminWidgetsHandler::class => [],
		],
	],
	'ready' => [
		'system' => [
			'_elgg_cache_init' => [],
		],
	],
	'update:after' => [
		'all' => [
			\Elgg\Comments\SyncContainerAccessHandler::class => [
				'priority' => 600,
			],
		],
		'group' => [
			\Elgg\Icons\MoveIconsOnOwnerChangeHandler::class => [],
			\Elgg\Icons\TouchIconsOnAccessChangeHandler::class => [],
		],
		'object' => [
			\Elgg\Icons\MoveIconsOnOwnerChangeHandler::class => [],
			\Elgg\Icons\TouchIconsOnAccessChangeHandler::class => [],
		],
	],
	'validate:after' => [
		'user' => [
			'Elgg\Users\Validation::notifyUserAfterValidation' => [],
		],
	],
];
