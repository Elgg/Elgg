<?php

namespace Elgg\Application;

use Elgg\Includer;
use Elgg\Project\Paths;

/**
 * Boostrap
 *
 * @internal
 */
class Bootstrap {

	/**
	 * @var \Closure[]
	 */
	private static $_setups = [];

	/**
	 * Are Elgg's global functions loaded?
	 *
	 * @return bool
	 */
	public static function isCoreLoaded() {
		return function_exists('elgg');
	}

	/**
	 * Define all Elgg global functions and constants, wire up boot events, but don't boot
	 *
	 * This includes all the .php files in engine/lib (not upgrades). If a script returns a function,
	 * it is queued and executed at the end.
	 *
	 * @return void
	 * @throws \RuntimeException
	 */
	public static function loadCore() {
		if (self::isCoreLoaded()) {
			return;
		}

		$path = Paths::elgg() . 'engine/lib';

		// include library files, capturing setup functions
		foreach (self::getEngineLibs() as $file) {
			try {
				self::requireSetupFileOnce("$path/$file");
			} catch (\Error $e) {
				throw new \RuntimeException("Elgg lib file failed include: engine/lib/$file");
			}
		}
	}

	/**
	 * Returns setups loaded from core files
	 * @return \Closure[]
	 */
	public static function getSetups() {
		return self::$_setups;
	}

	/**
	 * Require a library/plugin file once and capture returned anonymous functions
	 *
	 * @param string $file File to require
	 *
	 * @return mixed
	 * @internal
	 * @access private
	 */
	public static function requireSetupFileOnce($file) {
		$return = Includer::requireFileOnce($file);
		if ($return instanceof \Closure) {
			self::$_setups[] = $return;
		}

		return $return;
	}

	/**
	 * Get all engine/lib library filenames
	 *
	 * @note We can't just pull in all directory files because some users leave old files in place.
	 *
	 * @return string[]
	 */
	private static function getEngineLibs() {
		return [
			'elgglib.php',
			'access.php',
			'actions.php',
			'admin.php',
			'annotations.php',
			'cache.php',
			'comments.php',
			'configuration.php',
			'constants.php',
			'cron.php',
			'database.php',
			'deprecated-2.3.php',
			'deprecated-3.0.php',
			'entities.php',
			'filestore.php',
			'group.php',
			'input.php',
			'languages.php',
			'mb_wrapper.php',
			'metadata.php',
			'metastrings.php',
			'navigation.php',
			'notification.php',
			'output.php',
			'pagehandler.php',
			'pageowner.php',
			'pam.php',
			'plugins.php',
			'private_settings.php',
			'relationships.php',
			'river.php',
			'search.php',
			'sessions.php',
			'statistics.php',
			'tags.php',
			'upgrade.php',
			'user_settings.php',
			'users.php',
			'views.php',
			'widgets.php',
		];
	}
}
