<?php

namespace Elgg\Composer;

use Composer\Script\Event;
use Elgg\Filesystem\Directory\Local;

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
		self::copyFromElggToRoot('install/config/htaccess.dist', '.htaccess');
		self::copyFromElggToRoot('index.php', 'index.php');
		self::copyFromElggToRoot('install.php', 'install.php');
		self::copyFromElggToRoot('upgrade.php', 'upgrade.php');

		self::createProjectModFolder();

		$managed_plugins = \Elgg\Database\Plugins::BUNDLED_PLUGINS;

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
	protected static function copyFromElggToRoot($elggPath, $rootPath, $overwrite = false) {
		$from = Local::elggRoot()->getPath($elggPath);
		$to = Local::projectRoot()->getPath($rootPath);

		if (!$overwrite && file_exists($to)) {
			return false;
		}

		return copy($from, $to);
	}
	
	/**
	 * Make sure the /mod folder exists in when Elgg is installed through a Composer project
	 * eg. starter project
	 *
	 * @return bool
	 * @since 4.2
	 */
	protected static function createProjectModFolder(): bool {
		$project_mod = Local::projectRoot()->getPath('mod');
		$elgg_mod = Local::elggRoot()->getPath('mod');
		
		if ($project_mod === $elgg_mod) {
			// Elgg is the main project, no need to create the /mod folder
			return false;
		}
		
		if (is_dir($project_mod)) {
			// /mod folder already exists
			return false;
		}
		
		return mkdir($project_mod, 0755);
	}

	/**
	 * Make it possible for composer-managed Elgg site to recognize plugins
	 * version-controlled in Elgg core.
	 *
	 * @param string $plugin The name of the plugin to symlink
	 *
	 * @return bool Whether the symlink succeeded.
	 */
	protected static function symlinkPluginFromRootToElgg($plugin) {
		$link = Local::projectRoot()->getPath("mod/{$plugin}");
		$target = Local::elggRoot()->getPath("mod/{$plugin}");

		return is_dir($target) && !file_exists($link) && symlink($target, $link);
	}
}
