<?php
namespace Elgg\Project;

/**
 * Find Elgg and project paths.
 */
class Paths {

	/**
	 * Default settings filename
	 */
	const SETTINGS_PHP = 'settings.php';

	/**
	 * Path from project root to config folder
	 */
	const PATH_TO_CONFIG = 'elgg-config';

	/**
	 * Get the project root (where composer is installed) path with "/"
	 *
	 * @return string
	 */
	public static function project() {
		static $path;
		if ($path === null) {
			$path = self::elgg();

			// Assumes composer vendor location hasn't been customized...
			if (!is_file("{$path}vendor/autoload.php")) {
				$path = dirname(dirname(dirname($path))) . DIRECTORY_SEPARATOR;
			}
		}
		return $path;
	}

	/**
	 * Get the Elgg codebase path with "/"
	 *
	 * @return string
	 */
	public static function elgg() {
		return dirname(dirname(dirname(dirname(__DIR__)))) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the project's elgg-config/ path
	 *
	 * @return string
	 */
	public static function projectConfig() {
		return self::project() . self::PATH_TO_CONFIG . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get path of the Elgg settings file
	 *
	 * @param string $file File basename
	 * @return string
	 */
	public static function settingsFile($file = self::SETTINGS_PHP) {
		return self::projectConfig() . $file;
	}
}
