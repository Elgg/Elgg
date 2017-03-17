<?php
namespace Elgg\Filesystem;

use Elgg\Structs\Collection;

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
	 *
	 * @throws \InvalidArgumentException
	 */
	public function chroot($path);

	/**
	 * Read the file off the filesystem.
	 *
	 * @param string $path The directory-relative path to the target file.
	 *
	 * @return string Empty string if the file doesn't exist.
	 *
	 * @throws \InvalidArgumentException
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
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getFile($path);
	
	/**
	 * List the files in the given directory path.
	 *
	 * @param string $path      The subdirectory path within this directory
	 * @param bool   $recursive Find files recursively
	 *
	 * @return Collection<File>
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getFiles($path = '', $recursive = true);

	/**
	 * List the directories in the given directory path.
	 *
	 * @param string $path      The subdirectory path within this directory
	 * @param bool   $recursive Find directories recursively
	 *
	 * @return Collection<Directory>
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getDirectories($path = '', $recursive = true);

	/**
	 * Get the absolute path to the given directory-relative path.
	 *
	 * @param string $path A file/directory path within this directory.
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getPath($path = '');
	
	
	/**
	 * Do a PHP include of the file and return the result.
	 *
	 * NB: This only really works with local filesystems amirite?
	 *
	 * @param string $path Filesystem-relative path for the file to include.
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function includeFile($path);
	
	/**
	 * Whether this directory has an existing file at the given location.
	 *
	 * @param string $path The relative path within this directory
	 *
	 * @return boolean
	 *
	 * @throws \InvalidArgumentException
	 */
	public function isFile($path);
	
	/**
	 * Write a file, overwriting the contents if necessary.
	 *
	 * @param string $path    The path to the file.
	 * @param string $content The literal text content of the file.
	 *
	 * @return void
	 *
	 * @throws \InvalidArgumentException
	 */
	public function putContents($path, $content);
}
