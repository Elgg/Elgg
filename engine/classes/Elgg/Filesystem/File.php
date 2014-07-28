<?php
namespace Elgg\Filesystem;

use League\Flysystem;

/**
 * Represents a file that may or may not actually exist.
 * 
 * @package    Elgg.Core
 * @subpackage Filesystem
 * @since      1.10.0
 * 
 * @access private
 */
class File {
	
	/** @var Flysystem\Filesystem */
	private $filesystem;
	
	/** @var string */
	private $path;
	
	/**
	 * Constructor
	 * 
	 * @param Flysystem\Filesystem $filesystem The filesystem
	 * @param string               $path       The path to this file in the filesystem
	 */
	public function __construct(Flysystem\Filesystem $filesystem, $path) {
		$this->filesystem = $filesystem;
		$this->path = $path;
	}
	
	/**
	 * @return boolean Whether this file exists.
	 */
	public function exists() {
		return $this->filesystem->hasFile($this->path);
	}
	
	/**
	 * @return string The file's extension.
	 */
	public function getExtension() {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}
	
	/**
	 * @return string The file's basename.
	 */
	public function getBasename() {
		return pathinfo($this->path, PATHINFO_BASENAME);
	}
	
	/**
	 * Do a PHP include of the file and return the result.
	 * 
	 * TODO: This may only work for local filesystem?
	 * 
	 * @return mixed
	 */
	public function include() {
		return include $this->path;
	}
	
	/** @inheritDoc */
	public function __toString() {
		return $this->path;
	}
}
