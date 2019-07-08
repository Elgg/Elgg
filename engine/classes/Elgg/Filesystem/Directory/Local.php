<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\Directory;
use Elgg\Project\Paths;

/**
 * Namespace for generating local filesystems.
 *
 * @since 1.10.0
 * @internal
 */
final class Local {
	
	/**
	 * Shorthand for generating a new local filesystem.
	 *
	 * @param string $path Absolute path to directory on local filesystem.
	 *
	 * @return Directory
	 */
	public static function fromPath($path) {
		return Fly::createLocal($path);
	}

	/**
	 * Get the root composer install directory.
	 *
	 * @note This is not the same as the Elgg root! In the Elgg 1.x series, Elgg
	 * was always at the install root, but as of 2.0, Elgg can be installed as a
	 * composer dependency, so you cannot assume that it is at the root anymore.
	 *
	 * @return Directory
	 */
	public static function projectRoot() {
		static $dir;
		
		if ($dir === null) {
			$dir = self::fromPath(Paths::project());
		}
		
		return $dir;
	}

	/**
	 * Get the Elgg root directory.
	 *
	 * @note This is not the same as the project root! See projectRoot().
	 *
	 * @return Directory
	 */
	public static function elggRoot() {
		static $dir;

		if ($dir === null) {
			$dir = self::fromPath(Paths::elgg());
		}

		return $dir;
	}
}
