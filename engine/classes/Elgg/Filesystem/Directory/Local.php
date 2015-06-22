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
}