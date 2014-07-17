<?php
namespace Elgg\Filesystem;

/**
 * Represents a file that may or may not actually exist.
 * 
 * @since 1.10.0
 * 
 * @access private
 */
class File {
	
	/** @var Directory */
	private $directory;
	
	/** @var string */
	private $path;
	
	/**
	 * Constructor
	 * 
	 * @param Directory $directory The directory where this file resides
	 * @param string    $path      The path to this file relative to the directory
	 */
	public function __construct(Directory $directory, $path) {
		$this->directory = $directory;
		$this->path = $path;
	}
	
	/**
	 * Whether this file exists.
	 * 
	 * @return boolean 
	 */
	public function exists() {
		return $this->directory->isFile($this->path);
	}
	
	/**
	 * Get the part of the file after the directory, except the suffix.
	 * 
	 * @return string 
	 */
	public function getBasename($suffix = '') {
		return basename($this->path, $suffix);
	}
	
	/**
	 * Get the text content of this file. Empty string if it doesn't exist.
	 * 
	 * @return string
	 */
	public function getContents() {
		return $this->directory->getContents($this->path);
	}
	
	/**
	 * Get the directory path without the final file name.
	 * 
	 * @return string 
	 */
	public function getDirname() {
		return pathinfo($this->path, PATHINFO_DIRNAME);
	}
	
	/**
	 * Get the file's extension.
	 * 
	 * @return string 
	 */
	public function getExtension() {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}
	
	/**
	 * Get the entire path including that of the containing directory.
	 * 
	 * @return string 
	 */
	public function getFullPath() {
		return $this->directory->getFullPath($this->path);
	}
	
	/**
	 * Get the path relative to the containing directory.
	 * 
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * Do a PHP include of the file and return the result.
	 * 
	 * TODO(ewinslow): This may only work for local filesystems?
	 * 
	 * @return mixed
	 */
	public function includeFile() {
		return $this->directory->includeFile($this->path);
	}
	
	/**
	 * True if the file begins with a dot or is in a directory that begins with a dot.
	 * 
	 * @return bool 
	 */
	public function isPrivate() {
		return strpos($this->getPath(), "/.") !== false ||
			strpos($this->getPath(), ".") === 0;
	}
	
	/**
	 * Set the content of this file, overwriting old content if necessary.
	 * 
	 * @return void
	 */
	public function putContents($content) {
		$this->directory->putContents($this->path, $content);
	}
	
	/** @inheritDoc */
	public function __toString() {
		return $this->getFullPath();
	}
}
