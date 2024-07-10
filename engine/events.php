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
	'action:validate' => [
		'all' => [
			\Elgg\Entity\CropIcon::class => [],
		],
	],
	'all' => [
		'all' => [
			\Elgg\Notifications\EnqueueEventHandler::class => [
				'priority' => 700,
			],
		],
	],
	'attributes' => [
		'htmlawed' => [
			'\Elgg\Input\ValidateInputHandler::sanitizeStyles' => [],
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
		],
	],
	'cache:clear:before' => [
		'system' => [
			'\Elgg\Cache\EventHandlers::disable' => [],
		],
	],
	'cache:generate' => [
		'css' => [
			\Elgg\Views\PreProcessCssHandler::class => [],
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
	'container_logic_check' => [
		'all' => [
			\Elgg\Comments\ContainerLogicHandler::class => [],
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
	'create' => [
		'relationship' => [
			\Elgg\Friends\AddToAclHandler::class => [],
		],
	],
	'create:after' => [
		'all' => [
			\Elgg\Notifications\MentionsEnqueueEventHandler::class => [],
		],
		'object' => [
			\Elgg\Comments\AutoSubscribeHandler::class => [],
			\Elgg\Notifications\CreateContentEventHandler::class => [],
			\Elgg\Upgrade\CreateAdminNoticeHandler::class => [],
		],
		'river' => [
			\Elgg\Comments\UpdateRiverLastActionHandler::class => [],
			\Elgg\River\UpdateLastActionHandler::class => [],
		],
		'user' => [
			\Elgg\Friends\CreateAclHandler::class => [],
		],
	],
	'cron' => [
		'daily' => [
			\Elgg\Email\DelayedQueue\CronHandler::class => [],
			'Elgg\Users\Validation::removeUnvalidatedUsers' => [],
			\Elgg\Users\CleanupPersistentLoginHandler::class => [],
		],
		'hourly' => [
			\Elgg\Entity\RemoveDeletedEntitiesHandler::class => [],
		],
		'minute' => [
			\Elgg\Notifications\ProcessQueueCronHandler::class => ['priority' => 100],
		],
		'weekly' => [
			\Elgg\Email\DelayedQueue\CronHandler::class => [],
		],
	],
	'delete' => [
		'relationship' => [
			\Elgg\Friends\RemoveFromAclHandler::class => [],
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
		'page' => [
			\Elgg\Javascript\SetLightboxConfigHandler::class => [],
		],
	],
	'entity:favicon:sizes' => [
		'site' => [
			\Elgg\Icons\GetSiteFaviconSizesHandler::class => [],
		],
	],
	'entity:header:sizes' => [
		'all' => [
			\Elgg\Icons\HeaderSizesHandler::class => [],
		],
	],
	'entity:icon:file' => [
		'user' => [
			\Elgg\Icons\SetUserIconFileHandler::class => [],
		],
	],
	'entity:url' => [
		'object:widget' => [
			\Elgg\Widgets\EntityUrlHandler::class => [],
		],
	],
	'form:prepare:fields' => [
		'admin/security/security_txt' => [
			\Elgg\Forms\PrepareSecurityTxt::class => [],
		],
		'all' => [
			\Elgg\Forms\PrepareFields::class => ['priority' => 9999],
		],
	],
	'head' => [
		'page' => [
			\Elgg\Page\AddFaviconLinksHandler::class => [],
			\Elgg\Page\AddManifestLinkHandler::class => [],
			\Elgg\Page\AddMetasHandler::class => ['priority' => 20],
			\Elgg\Page\AddRssLinkHandler::class => ['priority' => 30],
			\Elgg\Page\AddTitleHandler::class => ['priority' => 10],
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
		'menu:admin_header' => [
			'Elgg\Menus\AdminHeader::prepareAdminAdministerUsersChildren' => [],
		],
		'menu:admin:users:bulk' => [
			'Elgg\Menus\AdminUsersBulk::disableItems' => [],
		],
		'menu:breadcrumbs' => [
			'\Elgg\Menus\Breadcrumbs::addHomeItem' => ['priority' => 10000],
			'\Elgg\Menus\Breadcrumbs::cleanupBreadcrumbs' => ['priority' => 9999],
		],
		'menu:site' => [
			'Elgg\Menus\Site::reorderItems' => [
				'priority' => 999,
			],
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
	'ready' => [
		'system' => [
			'\Elgg\Application\SystemEventHandlers::ready' => [],
		],
	],
	'register' => [
		'menu:admin_control_panel' => [
			'Elgg\Menus\AdminControlPanel::register' => [],
		],
		'menu:admin_header' => [
			'Elgg\Menus\AdminHeader::register' => [],
			'Elgg\Menus\AdminHeader::registerMaintenance' => [],
			'Elgg\Menus\AdminHeader::registerAdminAdminister' => [],
			'Elgg\Menus\AdminHeader::registerAdminConfigure' => [],
			'Elgg\Menus\AdminHeader::registerAdminDefaultWidgets' => [],
			'Elgg\Menus\AdminHeader::registerAdminInformation' => [],
			'Elgg\Menus\AdminHeader::registerAdminUtilities' => [],
		],
		'menu:admin_footer' => [
			'Elgg\Menus\AdminFooter::registerHelpResources' => [],
		],
		'menu:admin:users:bulk' => [
			'Elgg\Menus\AdminUsersBulk::registerActions' => [],
		],
		'menu:annotation' => [
			'Elgg\Menus\Annotation::registerDelete' => [],
		],
		'menu:entity' => [
			'Elgg\Menus\Entity::registerDelete' => [],
			'Elgg\Menus\Entity::registerEdit' => [],
			'Elgg\Menus\Entity::registerTrash' => ['priority' => 501], // needs to be after registerDelete
			'Elgg\Menus\Entity::registerUserHoverAdminSection' => [],
			'Elgg\Menus\UserHover::registerLoginAs' => [],
		],
		'menu:entity:object:comment' => [
			'Elgg\Menus\Entity::registerComment' => [],
		],
		'menu:entity:object:elgg_upgrade' => [
			'Elgg\Menus\Entity::registerUpgrade' => [],
		],
		'menu:entity:object:plugin' => [
			'Elgg\Menus\Entity::registerPlugin' => [],
		],
		'menu:entity:trash' => [
			'Elgg\Menus\Entity::registerDelete' => [],
			'Elgg\Menus\EntityTrash::registerRestore' => [],
		],
		'menu:entity_navigation' => [
			'Elgg\Menus\EntityNavigation::registerPreviousNext' => [],
		],
		'menu:filter:admin/upgrades' => [
			'Elgg\Menus\Filter::registerAdminUpgrades' => [],
		],
		'menu:filter:admin/users' => [
			'Elgg\Menus\Filter::registerAdminUsers' => [],
			'Elgg\Menus\FilterSortItems::registerTimeCreatedSorting' => [],
			'Elgg\Menus\FilterSortItems::registerNameSorting' => [],
			'Elgg\Menus\FilterSortItems::registerSortingDropdown' => ['priority' => 9999],
		],
		'menu:filter:filter' => [
			'Elgg\Menus\Filter::registerFilterTabs' => ['priority' => 1],
		],
		'menu:filter:profile/edit' => [
			'Elgg\Menus\Filter::registerAvatarEdit' => [],
		],
		'menu:filter:settings/notifications' => [
			'Elgg\Menus\Filter::registerNotificationSettings' => [],
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
			'Elgg\Menus\Page::registerAdminPluginSettings' => [],
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
			'Elgg\Menus\Title::registerEntityToTitle' => [
				'priority' => 600,
			],
		],
		'menu:topbar' => [
			'Elgg\Menus\Topbar::registerUserLinks' => [],
			'Elgg\Menus\Topbar::registerLogoutAs' => [],
			'Elgg\Menus\Topbar::registerMaintenance' => [],
		],
		'menu:user:unvalidated' => [
			'Elgg\Menus\UserUnvalidated::register' => [],
		],
		'menu:user_hover' => [
			'Elgg\Menus\UserHover::registerAvatarEdit' => [],
			'Elgg\Menus\UserHover::registerAdminActions' => [],
			'Elgg\Menus\UserHover::registerLoginAs' => [],
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
	'sanitize' => [
		'input' => [
			\Elgg\Input\ValidateInputHandler::class => [
				'priority' => 1,
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
			'\Elgg\Database\Seeds\Users::register' => ['priority' => 1],
			'\Elgg\Database\Seeds\Groups::register' => ['priority' => 2],
		],
	],
	'send' => [
		'notification:delayed_email' => [
			\Elgg\Email\DelayedQueue\EnqueueHandler::class => [],
		],
		'notification:email' => [
			\Elgg\Notifications\SendEmailHandler::class => [],
		],
	],
	'simplecache:generate' => [
		'css' => [
			\Elgg\Views\CalculateSRI::class => ['priority' => 999],
			\Elgg\Views\PreProcessCssHandler::class => [],
			\Elgg\Views\MinifyHandler::class => [],
		],
		'js' => [
			\Elgg\Views\CalculateSRI::class => ['priority' => 999],
			\Elgg\Views\MinifyHandler::class => [],
		],
	],
	'update:after' => [
		'all' => [
			\Elgg\Comments\SyncContainerAccessHandler::class => [
				'priority' => 600,
			],
			\Elgg\Notifications\MentionsEnqueueEventHandler::class => [],
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
	'usersettings:save' => [
		'user' => [
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
	'validate:after' => [
		'user' => [
			'Elgg\Users\Validation::addRiverActivityAfterValidation' => [],
		],
	],
	'view_vars' => [
		'elements/forms/help' => [
			\Elgg\Input\AddFileHelpTextHandler::class => [],
		],
		'input/password' => [
			\Elgg\Input\DisablePasswordAutocompleteHandler::class => [],
		],
		'output/icon' => [
			\Elgg\Icons\AddFontAwesomeClassesHandler::class => ['priority' => 100],
		],
		'page/components/list' => [
			'Elgg\Comments\Preloader::preload' => [],
		],
	],
];
