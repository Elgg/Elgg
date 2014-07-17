<?php
namespace Elgg\Filesystem;

/**
 * Represents a file that may or may not actually exist.
 * 
 * @since 1.10.0
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
	 * Indicates whether this file is present on the file system.
	 * 
	 * @return boolean 
	 */
	public function exists() {
		return $this->directory->isFile($this->path);
	}
	
	/**
	 * Returns the part of the file after the directory, except the suffix.
	 * 
	 * @param string $suffix The suffix to chop (e.g., '.php')
	 * 
	 * @return string 
	 */
	public function getBasename($suffix = '') {
		return basename($this->path, $suffix);
	}
	
	/**
	 * Returns the text content of this file. Empty string if it doesn't exist.
	 * 
	 * @return string
	 */
	public function getContents() {
		return $this->directory->getContents($this->path);
	}
	
	/**
	 * Returns the directory path without the final file name.
	 * 
	 * @return string 
	 */
	public function getDirname() {
		return pathinfo($this->path, PATHINFO_DIRNAME);
	}
	
	/**
	 * Returns the file's extension.
	 * 
	 * @return string 
	 */
	public function getExtension() {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}
	
	/**
	 * Returns the entire path including that of the containing directory.
	 * 
	 * @return string 
	 */
	public function getFullPath() {
		return $this->directory->getFullPath($this->path);
	}
	
	/**
	 * Returns the path relative to the containing directory.
	 * 
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * PHP-includes the file and returns the result.
	 * 
	 * TODO(ewinslow): This may only work for local filesystems?
	 * 
	 * @return mixed
	 */
	public function includeFile() {
		return $this->directory->includeFile($this->path);
	}
	
	/**
	 * Indicates whether the file is private.
	 * 
	 * Returns true if the file name begins with a dot.
	 * Returns true if the file is in a directory that begins with a dot.
	 * 
	 * @return bool 
	 */
	public function isPrivate() {
		return strpos($this->getPath(), "/.") !== false ||
			strpos($this->getPath(), ".") === 0;
	}
	
	/**
	 * Sets the content of this file.
	 * 
	 * Overwrites old content if the file exists.
	 * Creates the file if it does not exist.
	 * 
	 * @param string $content The literal content of the file
	 * 
	 * @return void
	 */
	public function putContents($content) {
		$this->directory->putContents($this->path, $content);
	}
	
	/**
	 * Returns the full path to this file.
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->getFullPath();
	}
}
