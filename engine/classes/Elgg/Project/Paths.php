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

		return self::sanitize($path);
	}

	/**
	 * Get temporary directory location
	 * @return string
	 */
	public static function temp() {
		$is_valid = function($dir) {
			if (!$dir) {
				return false;
			}

			$dir = self::sanitize($dir);
			return is_dir($dir) && is_writable($dir);
		};

		$temp = sys_get_temp_dir();
		if ($is_valid($temp)) {
			return self::sanitize($temp);
		}

		$temp = self::sanitize(self::project() . 'tmp/');
		if ($is_valid($temp)) {
			return $temp;
		}

		mkdir($temp);
		return $temp;
	}

	/**
	 * Get the Elgg codebase path with "/"
	 *
	 * @return string
	 */
	public static function elgg() {
		return self::sanitize(dirname(dirname(dirname(dirname(__DIR__)))));
	}

	/**
	 * Get the project's elgg-config/ path
	 *
	 * @return string
	 */
	public static function projectConfig() {
		return self::sanitize(self::project() . self::PATH_TO_CONFIG);
	}

	/**
	 * Get path of the Elgg settings file
	 *
	 * @param string $file File basename
	 *
	 * @return string
	 */
	public static function settingsFile($file = self::SETTINGS_PHP) {
		return self::projectConfig() . $file;
	}

	/**
	 * Sanitise file paths ensuring that they begin and end with slashes etc.
	 *
	 * @param string $path         The path
	 * @param bool   $append_slash Add tailing slash
	 *
	 * @return string
	 */
	public static function sanitize($path, $append_slash = true) {
		// Convert to correct UNIX paths
		$path = str_replace('\\', '/', $path);
		$path = str_replace('../', '/', $path);
		// replace // with / except when preceeded by :
		$path = preg_replace("/([^:])\/\//", "$1/", $path);

		// Sort trailing slash
		$path = trim($path);
		// rtrim defaults plus /
		$path = rtrim($path, " \n\t\0\x0B/");

		if ($append_slash) {
			$path = $path . '/';
		}

		return $path;
	}
}
