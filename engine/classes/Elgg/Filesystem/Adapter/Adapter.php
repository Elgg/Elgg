<?php
namespace Elgg\Filesystem\Adapter;
use Gaufrette\File;

/**
 * Data storage on local disks or remote systems
 */
interface Adapter {
	
	/**
	 * Creates a new file in the current directory.
	 * 
	 * @param string $name
	 * @return File
	 */
	public function file($name);
	
	/**
	 * Adds or changes to a new directory
	 * 
	 * @param string $path
	 * @return self
	 */
	public function directory($path);
	
	/**
	 * Gets a prefix based on $guid. This is used to
	 * split up directories on filesystems that have limits
	 * on the number of entries per directory.
	 * 
	 * @param int $guid
	 */
	public static function getPathPrefix($guid);
	
	/**
	 * Returns a normalized full path for the current directory
	 * 
	 * @param string $path
	 * @return string
	 */
	public static function fullPath($path);
}