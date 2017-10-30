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
	 * @return boolean Whether this file exists.
	 */
	public function exists() {
		return $this->directory->isFile($this->path);
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
		return $this->directory->getContents($this->path);
	}

	/**
	 * Put content into this file.
	 *
	 * @param string $content File content
	 * @return void
	 */
	public function putContents($content) {
		$this->directory->putContents($this->path, $content);
	}
	
	/**
	 * @return string The file's extension.
	 */
	public function getExtension() {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}
	
	/**
	 * @return string The full path to this file.
	 */
	public function getPath() {
		return $this->directory->getPath($this->path);
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
	 * {@inheritDoc}
	 */
	public function __toString() {
		return $this->path;
	}
}
