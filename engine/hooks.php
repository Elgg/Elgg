<?php
return [
	'access_collection:name' => [
		'access_collection' => [
			\Elgg\Friends\AclNameHandler::class => [],
		],
	],
	'action:validate' => [
		'all' => [
			\Elgg\Entity\CropIcon::class => [],
		],
	],
	'container_permissions_check' => [
		'all' => [
			\Elgg\Groups\MemberPermissionsHandler::class => [],
		],
		'object' => [
			\Elgg\Comments\ContainerPermissionsHandler::class => [],
			\Elgg\Widgets\DefaultWidgetsContainerPermissionsHandler::class => [],
		],
	],
	'cron' => [
		'daily' => [
			'_elgg_admin_notify_admins_pending_user_validation' => [],
			'_elgg_session_cleanup_persistent_login' => [],
		],
		'minute' => [
			\Elgg\Notifications\ProcessQueueCronHandler::class => ['priority' => 100],
		],
		'weekly' => [
			'_elgg_admin_notify_admins_pending_user_validation' => [],
		],
	],
	'diagnostics:report' => [
		'system' => [
			'Elgg\Diagnostics\Reports::getBasic' => ['priority' => 0],
			'Elgg\Diagnostics\Reports::getSigs' => ['priority' => 1],
			'Elgg\Diagnostics\Reports::getGlobals' => [],
			'Elgg\Diagnostics\Reports::getPHPInfo' => [],
		],
	],
	'email' => [
		'system' => [
			\Elgg\Comments\EmailSubjectHandler::class => [],
		],
	],
	'entity:icon:file' => [
		'user' => [
			'_elgg_user_set_icon_file' => [],
		],
	],
	'entity:url' => [
		'object' => [
			\Elgg\Widgets\EntityUrlHandler::class => [],
		],
	],
	'get' => [
		'subscriptions' => [
			'_elgg_admin_get_admin_subscribers_admin_action' => [],
			'_elgg_admin_get_user_subscriber_admin_action' => [],
			'\Elgg\Comments\CreateNotification::addOwnerToSubscribers' => [],
			'_elgg_user_get_subscriber_unban_action' => [],
		],
	],
	'head' => [
		'page' => [
			'_elgg_head_manifest' => [],
		],
	],
	'likes:is_likable' => [
		'object:comment' => [
			'Elgg\Values::getTrue' => [],
		],
	],
	'output' => [
		'page' => [
			\Elgg\Debug\Profiler::class => ['priority' => 999],
		],
	],
	'permissions_check' => [
		'object' => [
			\Elgg\Comments\EditPermissionsHandler::class => [],
		],
	],
	'permissions_check:comment' => [
		'object' => [
			\Elgg\Comments\GroupMemberPermissionsHandler::class => ['priority' => 999],
		],
	],
	'prepare' => [
		'breadcrumbs' => [
			'elgg_prepare_breadcrumbs' => [],
		],
		'menu:page' => [
			'Elgg\Menus\Page::cleanupUserSettingsPlugins' => [],
		],
		'menu:site' => [
			'Elgg\Menus\Site::reorderItems' => [
				'priority' => 999,
			],
		],
		'notification:create:object:comment' => [
			'Elgg\Comments\CreateNotification::prepareContentOwnerNotification' => [],
			'Elgg\Comments\CreateNotification::prepareNotification' => [],
		],
		'notification:make_admin:user:user' => [
			'_elgg_admin_prepare_admin_notification_make_admin' => [],
			'_elgg_admin_prepare_user_notification_make_admin' => [],
		],
		'notification:remove_admin:user:user' => [
			'_elgg_admin_prepare_admin_notification_remove_admin' => [],
			'_elgg_admin_prepare_user_notification_remove_admin' => [],
		],
		'notification:unban:user:user' => [
			'_elgg_user_prepare_unban_notification' => [],
		],
		'system:email' => [
			\Elgg\Email\DefaultMessageIdHeaderHandler::class => ['priority' => 1],
			\Elgg\Email\ThreadHeadersHandler::class => [],
		],
	],
	'public_pages' => [
		'walled_garden' => [
			'_elgg_nav_public_pages' => [],
		],
	],
	'register' => [
		'menu:admin_header' => [
			'Elgg\Menus\AdminHeader::register' => [],
			'Elgg\Menus\AdminHeader::registerMaintenance' => [],
		],
		'menu:admin_footer' => [
			'Elgg\Menus\AdminFooter::registerHelpResources' => [],
		],
		'menu:annotation' => [
			'Elgg\Menus\Annotation::registerDelete' => [],
		],
		'menu:entity' => [
			'Elgg\Menus\Entity::registerDelete' => [],
			'Elgg\Menus\Entity::registerEdit' => [],
			'Elgg\Menus\Entity::registerPlugin' => [],
			'Elgg\Menus\Entity::registerUpgrade' => ['priority' => 501],
		],
		'menu:entity_navigation' => [
			'Elgg\Menus\EntityNavigation::registerPreviousNext' => [],
		],
		'menu:filter:admin/upgrades' => [
			'Elgg\Menus\Filter::registerAdminUpgrades' => [],
		],
		'menu:footer' => [
			'Elgg\Menus\Footer::registerRSS' => [],
			'Elgg\Menus\Footer::registerElggBranding' => [],
		],
		'menu:login' => [
			'Elgg\Menus\Login::registerRegistration' => [],
			'Elgg\Menus\Login::registerResetPassword' => [],
		],
		'menu:page' => [
			'Elgg\Menus\Page::registerAdminAdminister' => [],
			'Elgg\Menus\Page::registerAdminConfigure' => [],
			'Elgg\Menus\Page::registerAdminDefaultWidgets' => [],
			'Elgg\Menus\Page::registerAdminInformation' => [],
			'Elgg\Menus\Page::registerAdminPluginSettings' => [],
			'Elgg\Menus\Page::registerAvatarEdit' => [],
			'Elgg\Menus\Page::registerUserSettings' => [],
			'Elgg\Menus\Page::registerUserSettingsPlugins' => [],
		],
		'menu:river' => [
			'Elgg\Menus\River::registerDelete' => [],
		],
		'menu:site' => [
			'Elgg\Menus\Site::registerAdminConfiguredItems' => [],
		],
		'menu:social' => [
			'Elgg\Menus\Social::registerComments' => [],
		],
		'menu:title' => [
			'Elgg\Menus\Title::registerAvatarEdit' => [],
		],
		'menu:topbar' => [
			'Elgg\Menus\Topbar::registerUserLinks' => [],
			'Elgg\Menus\Topbar::registerMaintenance' => [],
		],
		'menu:user:unvalidated' => [
			'Elgg\Menus\UserUnvalidated::register' => [],
		],
		'menu:user:unvalidated:bulk' => [
			'Elgg\Menus\UserUnvalidatedBulk::registerActions' => [],
		],
		'menu:user_hover' => [
			'Elgg\Menus\UserHover::registerAvatarEdit' => [],
			'Elgg\Menus\UserHover::registerAdminActions' => [],
		],
		'menu:walled_garden' => [
			'Elgg\Menus\WalledGarden::registerHome' => [],
		],
		'menu:widget' => [
			'Elgg\Menus\Widget::registerDelete' => [],
			'Elgg\Menus\Widget::registerEdit' => [],
		],
		'user' => [
			'_elgg_admin_check_admin_validation' => [
				'priority' => 999, // allow others to also disable the user
			],
		],
	],
	'response' => [
		'action:register' => [
			'_elgg_admin_set_registration_forward_url' => [
				'priority' => 999, // allow other to set forwar url first
			],
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
	'seeds' => [
		'database' => [
			'_elgg_db_register_seeds' => ['priority' => 1],
		],
	],
	'send' => [
		'notification:email' => [
			\Elgg\Notifications\SendEmailHandler::class => [],
		],
	],
	'usersettings:save' => [
		'user' => [
			'_elgg_admin_save_notification_setting' => [],
			\Elgg\Notifications\SaveUserSettingsHandler::class => [],
			'Elgg\Users\Settings::setDefaultAccess' => [],
			'Elgg\Users\Settings::setEmail' => [],
			'Elgg\Users\Settings::setLanguage' => [],
			'Elgg\Users\Settings::setName' => [],
			'Elgg\Users\Settings::setPassword' => [
				'priority' => 100, // this needs to be before email change, for security reasons
			],
			'Elgg\Users\Settings::setUsername' => [],
		],
	],
	'validate' => [
		'input' => [
			'_elgg_htmlawed_filter_tags' => [
				'priority' => 1,
			],
		],
	],
	'view_vars' => [
		'input/password' => [
			'_elgg_disable_password_autocomplete' => [],
		],
	],
];
