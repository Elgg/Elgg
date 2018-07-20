<?php
/**
 * Return a list of class names of asynchronous core upgrades. Any unfinished upgrades will
 * be presented to the site administrator in order of appearance here.
 *
 * @see Elgg\Upgrade\Locator
 */

return [
	\Elgg\Upgrades\DeleteOldPlugins::class,
	\Elgg\Upgrades\ActivateNewPlugins::class,
	\Elgg\Upgrades\AlterDatabaseToMultiByteCharset::class,
	\Elgg\Upgrades\SetSecurityConfigDefaults::class,
	\Elgg\Upgrades\MigrateFriendsACL::class,
	\Elgg\Upgrades\MigrateCronLog::class,
];
