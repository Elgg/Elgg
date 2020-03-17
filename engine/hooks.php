<?php
return [
	'access_collection:name' => [
		'access_collection' => [
			\Elgg\Friends\AclNameHandler::class => [],
		],
	],
	'access:collections:write' => [
		'all' => [
			\Elgg\WalledGarden\RemovePublicAccessHandler::class => ['priority' => 9999],
		],
	],
	'action' => [
		'all' => [
			'Elgg\Maintenance\Routing::preventAction' => ['priority' => 600],
		],
	],
	'action:validate' => [
		'all' => [
			\Elgg\Entity\CropIcon::class => [],
		],
	],
	'cache:generate' => [
		'css' => [
			\Elgg\Views\PreProcessCssHandler::class => [],
		],
		'js' => [
			\Elgg\Views\AddAmdModuleNameHandler::class => [],
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
			'Elgg\Users\Validation::notifyAdminsAboutPendingUsers' => [],
			\Elgg\Users\CleanupPersistentLoginHandler::class => [],
		],
		'minute' => [
			\Elgg\Notifications\ProcessQueueCronHandler::class => ['priority' => 100],
		],
		'weekly' => [
			'Elgg\Users\Validation::notifyAdminsAboutPendingUsers' => [],
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
	'elgg.data' => [
		'site' => [
			\Elgg\Javascript\SetLightboxConfigHandler::class => [],
		],
	],
	'email' => [
		'system' => [
			\Elgg\Comments\EmailSubjectHandler::class => [],
		],
	],
	'entity:icon:file' => [
		'user' => [
			\Elgg\Icons\SetUserIconFileHandler::class => [],
		],
	],
	'entity:url' => [
		'object' => [
			\Elgg\Widgets\EntityUrlHandler::class => [],
		],
	],
	'get' => [
		'subscriptions' => [
			'Elgg\Notifications\ChangeAdminNotification::addSiteAdminSubscribers' => [],
			'Elgg\Notifications\ChangeAdminNotification::addUserSubscriber' => [],
			'Elgg\Comments\CreateNotification::addOwnerToSubscribers' => [],
			'_elgg_user_get_subscriber_unban_action' => [],
		],
	],
	'head' => [
		'page' => [
			\Elgg\Page\AddFaviconLinksHandler::class => [],
			\Elgg\Page\AddManifestLinkHandler::class => [],
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
	'output:before' => [
		'page' => [
			\Elgg\Page\SetXFrameOptionsHeaderHandler::class => [],
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
			\Elgg\Page\PrepareBreadcrumbsHandler::class => [],
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
			'Elgg\Notifications\ChangeAdminNotification::prepareMakeAdminNotificationToAdmin' => [],
			'Elgg\Notifications\ChangeAdminNotification::prepareMakeAdminNotificationToUser' => [],
		],
		'notification:remove_admin:user:user' => [
			'Elgg\Notifications\ChangeAdminNotification::prepareRemoveAdminNotificationToAdmin' => [],
			'Elgg\Notifications\ChangeAdminNotification::prepareRemoveAdminNotificationToUser' => [],
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
			\Elgg\WalledGarden\ExtendPublicPagesHandler::class => [],
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
			'Elgg\Users\Validation::checkAdminValidation' => [
				'priority' => 999, // allow others to also disable the user
			],
		],
	],
	'response' => [
		'action:register' => [
			'Elgg\Users\Validation::setRegistrationForwardUrl' => [
				'priority' => 999, // allow other to set forwar url first
			],
		],
	],
	'route' => [
		'all' => [
			'Elgg\Maintenance\Routing::redirectRoute' => ['priority' => 600],
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
			'\Elgg\Database\Seeds\Users::register' => ['priority' => 1],
			'\Elgg\Database\Seeds\Groups::register' => ['priority' => 2],
		],
	],
	'send' => [
		'notification:email' => [
			\Elgg\Notifications\SendEmailHandler::class => [],
		],
	],
	'simplecache:generate' => [
		'css' => [
			\Elgg\Views\PreProcessCssHandler::class => [],
			\Elgg\Views\MinifyHandler::class => [],
		],
		'js' => [
			\Elgg\Views\AddAmdModuleNameHandler::class => [],
			\Elgg\Views\MinifyHandler::class => [],
		],
	],
	'usersettings:save' => [
		'user' => [
			'Elgg\Users\Settings::setAdminValidationNotification' => [],
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
			\Elgg\Input\ValidateInputHandler::class => [
				'priority' => 1,
			],
		],
	],
	'view_vars' => [
		'elements/forms/help' => [
			\Elgg\Input\AddFileHelpTextHandler::class => [],
		],
		'input/password' => [
			\Elgg\Input\DisablePasswordAutocompleteHandler::class => [],
		],
	],
];
