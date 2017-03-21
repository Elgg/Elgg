<?php
namespace Elgg\Filesystem\Directory;

use Elgg\Filesystem\Directory;

/**
 * Namespace for generating in-memory filesystems.
 *
 * @since 1.10.0
 *
 * @access private
 */
final class InMemory {

	/**
	 * Shorthand for generating a new in-memory-only filesystem.
	 *
	 * @param array $files A structure like  ['/path' => 'contents']
	 *
	 * @return Directory
	 */
	public static function fromArray(array $files) /*: Directory*/ {
		$dir = Fly::createInMemory();
		
		foreach ($files as $file => $content) {
			$dir->putContents($file, $content);
		}
		
		return $dir;
	}
}
