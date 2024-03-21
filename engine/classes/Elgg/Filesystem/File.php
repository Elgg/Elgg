<?php

namespace Elgg\Filesystem;

/**
 * Represents a file that may or may not actually exist.
 *
 * @since 1.10.0
 * @internal
 */
class File {
	
	/**
	 * Constructor
	 *
	 * @param Directory $directory The directory where this file resides
	 * @param string    $path      The path to this file relative to the directory
	 */
	public function __construct(protected Directory $directory, protected string $path) {
	}
	
	/**
	 * @return boolean Whether this file exists.
	 */
	public function exists(): bool {
		return $this->directory->isFile($this->path);
	}
	
	/**
	 * @return string The file's basename.
	 */
	public function getBasename(): string {
		return pathinfo($this->path, PATHINFO_BASENAME);
	}
	
	/**
	 * Get the text content of this file. Empty string if it doesn't exist.
	 *
	 * @return string
	 */
	public function getContents(): string {
		return $this->directory->getContents($this->path);
	}

	/**
	 * Put content into this file.
	 *
	 * @param string $content File content
	 * @return void
	 */
	public function putContents($content): void {
		$this->directory->putContents($this->path, $content);
	}
	
	/**
	 * @return string The file's extension.
	 */
	public function getExtension(): string {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}
	
	/**
	 * @return string The full path to this file.
	 */
	public function getPath(): string {
		return $this->directory->getPath($this->path);
	}
	
	/**
	 * Do a PHP include of the file and return the result.
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
