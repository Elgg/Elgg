<?php
namespace Elgg\Filesystem;

/**
 * A simple directory abstraction.
 * 
 * @since 1.10.2
 * 
 * @access private
 */
interface Directory {

	/**
	 * Returns a subdirectory with access limited to the given directory.
	 * 
	 * @param string $path The path relative to this directory to chroot to.
	 * 
	 * @return Directory A new directory instance.
	 */
	public function chroot($path);

	/**
	 * Read the file off the filesystem.
	 * 
	 * @param string $path The directory-relative path to the target file.
	 * 
	 * @return string Empty string if the file doesn't exist.
	 */
	public function getContents($path);
	
	/**
	 * A reference to the file at the given path, even if it doesn't exist yet.
	 * 
	 * However, will throw an exception if the file is already a directory.
	 * 
	 * @param string $path The path to the file, relative to this directory.
	 * 
	 * @return File
	 */
	public function getFile($path);
	
	/**
	 * Recursively list the files in the given directory path.
	 * 
	 * @param string $path The subdirectory path within this directory
	 * 
	 * @return Collection<File>
	 */
	public function getFiles($path = '');

	/**
	 * Do a PHP include of the file and return the result.
	 * 
	 * NB: This only really works with local filesystems amirite?
	 * 
	 * @param string $path Filesystem-relative path for the file to include.
	 * 
	 * @return mixed
	 */
	public function includeFile($path);
	
	/**
	 * Whether this directory has an existing file at the given location.
	 * 
	 * @param string $path The relative path within this directory
	 * 
	 * @return boolean
	 */
	public function isFile($path);
	
	/**
	 * Write a file, overwriting the contents if necessary.
	 * 
	 * @param string $path    The path to the file.
	 * @param string $content The literal text content of the file.
	 * 
	 * @return void
	 */
	public function putContents($path, $content);
}
