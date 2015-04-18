<?php
namespace Elgg\Filesystem;

use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter as GaufretteAdapter;
use Elgg\EntityDirLocator;

/**
 * Data storage on local disks or remote systems
 */
class Adapter {
	/**
	 * @var GaufretteAdapter
	 */
	private $adapter;
	
	/**
	 * @var string
	 */
	private $path;
	
	/**
	 * @var Filesystem
	 */
	private $fs;
	
	/**
	 * Create a new instance of a local filesystem
	 * 
	 * @param GaufretteLocal $adapter A GaufretteLocal adapter
	 * @param string         $path    The current path
	 */
	public function __construct(GaufretteAdapter $adapter, $path = '') {
		$this->adapter = $adapter;
		$this->path = $path;
		$this->fs = new Filesystem($adapter);
	}
	
	/**
	 * Creates a new file in the current directory.
	 * 
	 * @param string $name
	 * @return File
	 */
	public function file($name) {
		return new File($this->fullPath($name), $this->fs);
	}
	
	/**
	 * Adds or changes to a new directory
	 * 
	 * @param string $path
	 * @return self
	 */
	public function directory($path) {
		return new self($this->adapter, $this->fullPath($path));
	}
	
	/**
	 * Gets a prefix based on $guid. This is used to
	 * split up directories on filesystems that have limits
	 * on the number of entries per directory.
	 * 
	 * @param int $guid
	 */
	public static function getPathPrefix($guid) {
		$locator = new EntityDirLocator($guid);
		return "$locator";
	}
	
	/**
	 * Returns a normalized full path for the current directory
	 * 
	 * @param string $path
	 * @return string
	 */
	public function fullPath($path) {
		return str_replace("//", "/", "{$this->path}/$path");
	}
}