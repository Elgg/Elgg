<?php
namespace Elgg\Composer;

use Composer\Script\Event;
use Elgg;
use Elgg\Filesystem\Directory;

/**
 * A composer command handler to run after composer install
 */
class PostInstall {
	/**
	 * Copies files that Elgg expects to be in the root directory.
	 *
	 * @param Event $event The Composer event (install/upgrade)
	 *
	 * @return void
	 */
	public static function execute(Event $event) {
		self::copyFromElggToRoot("install/config/htaccess.dist", ".htaccess");
		self::copyFromElggToRoot("index.php", "index.php");
		self::copyFromElggToRoot("install.php", "install.php");
		self::copyFromElggToRoot("upgrade.php", "upgrade.php");

		$managed_plugins = [
			'activity',
			'blog',
			'bookmarks',
			'ckeditor',
			'custom_index',
			'dashboard',
			'developers',
			'diagnostics',
			'discussions',
			'embed',
			'externalpages',
			'file',
			'friends',
			'friends_collections',
			'garbagecollector',
			'groups',
			'invitefriends',
			'likes',
			'members',
			'messageboard',
			'messages',
			'notifications',
			'pages',
			'profile',
			'reportedcontent',
			'search',
			'site_notifications',
			'system_log',
			'tagcloud',
			'thewire',
			'uservalidationbyemail',
			'web_services',
		];

		foreach ($managed_plugins as $plugin) {
			self::symlinkPluginFromRootToElgg($plugin);
		}
	}

	/**
	 * Copies a file from the given location in Elgg to the given location in root.
	 *
	 * @param string $elggPath  Path relative to elgg dir.
	 * @param string $rootPath  Path relative to app root dir.
	 * @param bool   $overwrite Overwrite file if it exists in root path, defaults to false.
	 *
	 * @return boolean Whether the copy succeeded.
	 */
	private static function copyFromElggToRoot($elggPath, $rootPath, $overwrite = false) {
		$from = Elgg\Application::elggDir()->getPath($elggPath);
		$to = Directory\Local::projectRoot()->getPath($rootPath);

		if (!$overwrite && file_exists($to)) {
			return false;
		}

		return copy($from, $to);
	}

	/**
	 * Make it possible for composer-managed Elgg site to recognize plugins
	 * version-controlled in Elgg core.
	 *
	 * @param string $plugin The name of the plugin to symlink
	 *
	 * @return bool Whether the symlink succeeded.
	 */
	private static function symlinkPluginFromRootToElgg($plugin) {
		$from = Directory\Local::projectRoot()->getPath("mod/$plugin");
		$to = Elgg\Application::elggDir()->getPath("mod/$plugin");

		return !file_exists($from) && symlink($to, $from);
	}
}
