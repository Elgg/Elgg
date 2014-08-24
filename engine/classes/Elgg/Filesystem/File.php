<?php
namespace Elgg\Filesystem;

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
	
	/** @var Filesystem */
	private $filesystem;
	
	/** @var string */
	private $path;
	
	/**
	 * Constructor
	 * 
	 * @param Filesystem $filesystem The filesystem
	 * @param string     $path       The path to this file in the filesystem
	 */
	public function __construct(Filesystem $filesystem, $path) {
		$this->filesystem = $filesystem;
		$this->path = $path;
	}
	
	/**
	 * @return boolean Whether this file exists.
	 */
	public function exists() {
		return $this->filesystem->isFile($this->path);
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
	 * Get the text content of this file. Empty string if it doesn't exist.
	 * 
	 * @return string
	 */
	public function getContents() {
		return $this->filesystem->getFileContents($this->path);
	}
	
	/**
	 * Do a PHP include of the file and return the result.
	 * 
	 * TODO: This may only work for local filesystem?
	 * 
	 * @return mixed
	 */
	public function includeFile() {
		return $this->filesystem->includeFile($this->path);
	}
	
	/** @inheritDoc */
	public function __toString() {
		return $this->path;
	}
}
