<?php
namespace Elgg\Filesystem;

/**
 * Represents a directory that may or may not actually exist.
 * 
 * @package    Elgg.Core
 * @subpackage Filesystem
 * @since      1.10.0
 * 
 * @access private
 */
class Directory {
	
	/** @var Filesystem */
	private $filesystem;
	
	/** @var string */
	private $path;
	
	/**
	 * Constructor
	 * 
	 * @param Filesystem $filesystem The filesystem
	 * @param string     $path       The path to this dir within the filesystem
	 */
	public function __construct(Filesystem $filesystem, $path) {
		$this->filesystem = $filesystem;
		$this->path = rtrim($path, "\\/");
	}
	
	/** 
	 * Get a representation of the file at the given path within this directory.
	 * 
	 * @param string $path The path the to file
	 * 
	 * @return File
	 */
	public function getFile($path) {
		return new File($this->filesystem, "$this->path/$path");
	}
	
	/** 
	 * Get the list of files in this directory
	 * 
	 * @return File[]
	 */
	public function getFiles() {
		return $this->filesystem->getFiles($this->path);
	}
}
