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
	'likes:is_likable' => [
		'object:comment' => [
			'Elgg\Values::getTrue' => [],
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
			'_elgg_user_settings_menu_prepare' => [],
		],
		'menu:relationship' => [
			'_elgg_menu_transform_to_dropdown' => [],
		],
		'menu:river' => [
			'_elgg_menu_transform_to_dropdown' => [],
		],
		'menu:site' => [
			'_elgg_site_menu_setup' => [
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
			'_elgg_admin_header_menu' => [],
		],
		'menu:admin_footer' => [
			'_elgg_admin_footer_menu' => [],
		],
		'menu:annotation' => [
			'_elgg_annotations_default_menu_items' => [],
		],
		'menu:entity' => [
			'_elgg_entity_menu_setup' => [],
			'_elgg_plugin_entity_menu_setup' => [],
			'_elgg_upgrade_entity_menu' => ['priority' => 501],
		],
		'menu:entity_navigation' => [
			'_elgg_entity_navigation_menu_setup' => [],
		],
		'menu:filter:admin/upgrades' => [
			'_elgg_admin_upgrades_menu' => [],
		],
		'menu:footer' => [
			'_elgg_rss_menu_setup' => [],
		],
		'menu:login' => [
			'_elgg_login_menu_setup' => [],
		],
		'menu:page' => [
			'_elgg_admin_page_menu' => [],
			'_elgg_admin_page_menu_plugin_settings' => [],
			'_elgg_user_page_menu' => [],
			'_elgg_user_settings_menu_register' => [],
		],
		'menu:river' => [
			'_elgg_river_menu_setup' => [],
		],
		'menu:site' => [
			'_elgg_site_menu_init' => [],
		],
		'menu:social' => [
			'_elgg_comments_social_menu_setup' => [],
		],
		'menu:title' => [
			'_elgg_user_title_menu' => [],
		],
		'menu:topbar' => [
			'_elgg_user_topbar_menu' => [],
		],
		'menu:user:unvalidated' => [
			'_elgg_user_unvalidated_menu' => [],
		],
		'menu:user:unvalidated:bulk' => [
			'_elgg_admin_user_unvalidated_bulk_menu' => [],
		],
		'menu:user_hover' => [
			'elgg_user_hover_menu' => [],
		],
		'menu:widget' => [
			'_elgg_widget_menu_setup' => [],
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
