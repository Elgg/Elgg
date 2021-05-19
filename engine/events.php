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
			\Elgg\Users\BanUserNotificationHandler::class => [],
		],
	],
	'cache:clear' => [
		'system' => [
			'\Elgg\Cache\EventHandlers::clear' => [],
		],
	],
	'cache:clear:after' => [
		'system' => [
			'\Elgg\Cache\EventHandlers::enable' => [],
			'\Elgg\Cache\EventHandlers::rebuildPublicContainer' => [],
		],
	],
	'cache:clear:before' => [
		'system' => [
			'\Elgg\Cache\EventHandlers::disable' => [],
		],
	],
	'cache:invalidate' => [
		'system' => [
			'\Elgg\Cache\EventHandlers::invalidate' => [],
		],
	],
	'cache:purge' => [
		'system' => [
			'\Elgg\Cache\EventHandlers::purge' => [],
		],
	],
	'complete' => [
		'upgrade' => [
			\Elgg\Upgrade\UpgradeCompletedAdminNoticeHandler::class => [],
		],
	],
	'create' => [
		'object' => [
			\Elgg\Comments\AutoSubscribeHandler::class => [],
			\Elgg\Notifications\CreateContentEventHandler::class => [],
			\Elgg\Upgrade\CreateAdminNoticeHandler::class => [],
		],
		'relationship' => [
			\Elgg\Friends\AddToAclHandler::class => [],
		],
		'user' => [
			\Elgg\Friends\CreateAclHandler::class => [],
		],
	],
	'create:after' => [
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
			'Elgg\Application\SystemEventHandlers::initEarly' => ['priority' => 0],
			'Elgg\Application\SystemEventHandlers::init' => [],
			'Elgg\Application\SystemEventHandlers::initLate' => ['priority' => 1000],
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
			'\Elgg\Application\SystemEventHandlers::ready' => [],
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
