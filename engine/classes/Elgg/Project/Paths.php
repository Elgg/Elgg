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
	public static function project(): string {
		static $path;
		if ($path === null) {
			$path = self::elgg();

			// Assumes composer vendor location hasn't been customized...
			if (!is_file("{$path}vendor/autoload.php")) {
				$path = dirname($path, 3) . DIRECTORY_SEPARATOR;
			}
		}

		return $path;
	}

	/**
	 * Get the Elgg codebase path with "/"
	 *
	 * @return string
	 */
	public static function elgg(): string {
		return dirname(__DIR__, 4) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the project's elgg-config/ path
	 *
	 * @return string
	 */
	public static function projectConfig(): string {
		return self::project() . self::PATH_TO_CONFIG . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get path of the Elgg settings file
	 *
	 * @param string $file File basename
	 *
	 * @return string
	 */
	public static function settingsFile($file = self::SETTINGS_PHP): string {
		return self::projectConfig() . $file;
	}

	/**
	 * Sanitize file paths ensuring that they begin and end with slashes etc.
	 *
	 * @param string $path         The path
	 * @param bool   $append_slash Add trailing slash
	 *
	 * @return string
	 */
	public static function sanitize($path, $append_slash = true): string {
		$path = (string) $path;
		
		// Convert to correct UNIX paths
		$path = str_replace('\\', '/', $path);
		// replace ./ to / to prevent directory traversal
		$path = preg_replace('/[.]+\//', '/', $path);
		// replace // with / except when preceeded by :
		$path = preg_replace('/([^:])[\/]{2,}/', '$1/', $path);

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
