<?php
/**
 * Return a list of class names of asynchronous core upgrades. Any unfinished upgrades will
 * be presented to the site administrator in order of appearance here.
 *
 * @see Elgg\Upgrade\Locator
 */

return [
	\Elgg\Upgrades\AlterDatabaseToMultiByteCharset::class,
	\Elgg\Upgrades\ChangeUserNotificationSettingsNamespace::class,
	\Elgg\Upgrades\ContentOwnerSubscriptions::class,
	\Elgg\Upgrades\DeleteDiagnosticsPlugin::class,
	\Elgg\Upgrades\DeleteNotificationsPlugin::class,
	\Elgg\Upgrades\MigrateACLNotificationPreferences::class,
	\Elgg\Upgrades\MigrateEntityIconCroppingCoordinates::class,
	\Elgg\Upgrades\NotificationsPrefix::class,
	\Elgg\Upgrades\RemoveIcontime::class,
	\Elgg\Upgrades\RemoveOrphanedThreadedComments::class,
	\Elgg\Upgrades\MigrateDebugConfig::class,
	\Elgg\Upgrades\MigrateAdminValidationNotificationPreference::class,
];
