<?php
namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\Directory;

/**
 * Namespace for generating local filesystems.
 *
 * @since 1.10.0
 *
 * @access private
 */
final class Local {
	
	/**
	 * Shorthand for generating a new local filesystem.
	 *
	 * @param string $path Absolute path to directory on local filesystem.
	 *
	 * @return Directory
	 */
	public static function fromPath(/*string*/ $path) /*: Directory*/ {
		return Fly::createLocal($path);
	}

	/**
	 * Returns a directory that points to the root composer install.
	 * 
	 * Note: This is not the same as the Elgg root! In the Elgg 1.x series, Elgg
	 * was always at the install root, but as of 2.0, Elgg can be installed as a
	 * composer dependency, so you cannot assume that it is at the root anymore.
	 *
	 * @return Directory
	 */
	public static function root() /*: Directory*/ {
		static $dir;
		
		if (!isset($dir)) {
			$dir = self::fromPath(realpath(__DIR__ . '/../../../../..'));
			// Assumes composer vendor location hasn't been customized...
			if (!$dir->isFile('vendor/autoload.php')) {
				// Assume we're is installed at vendor/{vendor}/{package}
				$dir = self::fromPath(realpath($dir->getPath() . '/../../..'));
			}
		}
		
		return $dir;
	}
}