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
		'object' => [
			'_elgg_comments_container_permissions_override' => [],
		],
	],
	'cron' => [
		'daily' => [
			'_elgg_admin_notify_admins_pending_user_validation' => [],
			'_elgg_session_cleanup_persistent_login' => [],
		],
		'minute' => [
			'_elgg_notifications_cron' => [
				'priority' => 100,
			],
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
			'_elgg_comments_notification_email_subject' => [],
		],
	],
	'entity:icon:file' => [
		'user' => [
			'_elgg_user_set_icon_file' => [],
		],
	],
	'entity:url' => [
		'object' => [
			'_elgg_widgets_widget_urls' => [],
		],
	],
	'get' => [
		'subscriptions' => [
			'_elgg_admin_get_admin_subscribers_admin_action' => [],
			'_elgg_admin_get_user_subscriber_admin_action' => [],
			'_elgg_comments_add_content_owner_to_subscriptions' => [],
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
			'_elgg_comments_permissions_override' => [],
		],
	],
	'permissions_check:comment' => [
		'object' => [
			'_elgg_groups_comment_permissions_override' => ['priority' => 999],
		],
	],
	'prepare' => [
		'breadcrumbs' => [
			'elgg_prepare_breadcrumbs' => [],
		],
		'menu:annotation' => [
			'_elgg_menu_transform_to_dropdown' => [],
		],
		'menu:entity' => [
			'_elgg_menu_transform_to_dropdown' => [],
		],
		'menu:owner_block' => [
			'_elgg_setup_vertical_menu' => [
				'priority' => 999,
			],
		],
		'menu:page' => [
			'_elgg_setup_vertical_menu' => [
				'priority' => 999,
			],
			'Elgg\Menus\Page::cleanupUserSettingsPlugins' => [],
		],
		'menu:relationship' => [
			'_elgg_menu_transform_to_dropdown' => [],
		],
		'menu:river' => [
			'_elgg_menu_transform_to_dropdown' => [],
		],
		'menu:site' => [
			'Elgg\Menus\Site::reorderItems' => [
				'priority' => 999,
			],
		],
		'notification:create:object:comment' => [
			'_elgg_comments_prepare_content_owner_notification' => [],
			'_elgg_comments_prepare_notification' => [],
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
			'_elgg_notifications_smtp_default_message_id_header' => [
				'priority' => 1,
			],
			'_elgg_notifications_smtp_thread_headers' => [],
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
			'_elgg_send_email_notification' => [],
		],
	],
	'usersettings:save' => [
		'user' => [
			'_elgg_admin_save_notification_setting' => [],
			'_elgg_save_notification_user_settings' => [],
			'_elgg_set_user_default_access' => [],
			'_elgg_set_user_email' => [],
			'_elgg_set_user_language' => [],
			'_elgg_set_user_name' => [],
			'_elgg_set_user_password' => [
				'priority' => 100, // this needs to be before email change, for security reasons
			],
			'_elgg_set_user_username' => [],
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
