<?php
namespace Elgg\Filesystem;

use Gaufrette\Adapter\Local;
use Gaufrette\Adapter\InMemory;
use Gaufrette\Filesystem as Gaufrette;

/**
 * A simple filesystem abstraction.
 * 
 * We've worked really hard to privatize the fact that this uses Gaufrette under the hood.
 * This allows us to switch it out with something later if we need to.
 * Changes to this class should maintain this level of privacy.
 * 
 * @package    Elgg.Core
 * @subpackage Filesystem
 * @since      1.10.0
 * 
 * @access private
 */
final class Filesystem {
	
	/** @var Gaufrette */
	private $gaufrette;
	
	/** @var string */
	private $localPath;
	
	/** @var string Path relative to the gaufrette filesystem's root */
	private $chroot;
	
	/**
	 * Use one of the static factory functions to create an instance.
	 * 
	 * @param Gaufrette $gaufrette The underlying filesystem implementation.
	 * @param string    $localPath Only applicable for local filesystem.
	 * @param string    $chroot    Path relative to the gaufrette filesystem's root.
	 */
	private function __construct(Gaufrette $gaufrette, $localPath = '', $chroot = '') {
		$this->gaufrette = $gaufrette;
		$this->localPath = rtrim($localPath, "/\\");
		$this->chroot = $this->normalize($chroot);
	}
	
	/**
	 * Whether this filesystem has an existing file at the given location.
	 * 
	 * @param string $path The relative path within this filesystem
	 * 
	 * @return boolean
	 */
	public function isFile($path) {
		return !$this->isDirectory($path) &&
			$this->gaufrette->has($this->getGaufrettePath($path));
	}
	
	/**
	 * Whether this filesystem has an existing directory at the given path.
	 * 
	 * @param string $path The path to the directory, relative to this filesystem.
	 * 
	 * @return boolean
	 */
	private function isDirectory($path) {
		$adapter = $this->gaufrette->getAdapter();
		return $adapter->isDirectory($this->getGaufrettePath($path));
	}
	
	/**
	 * A reference to the file at the given path, even if it doesn't exist yet.
	 * 
	 * However, will throw an exception if the file is already a directory.
	 * 
	 * @param string $path The path to the file, relative to this filesystem.
	 * 
	 * @return File
	 */
	public function getFile($path) {
		if ($this->isDirectory($path)) {
			throw new \RuntimeException("There is already a directory at that location: $path");
		}
		
		return new File($this, $path);
	}
	
	/**
	 * Read the file off the filesystem.
	 * 
	 * @param string $path The filesyste-relative path to the target file.
	 * 
	 * @return string Empty string if the file doesn't exist.
	 */
	public function getFileContents($path) {
		try {
			return $this->gaufrette->read($this->getGaufrettePath($path));
		} catch (\Exception $e) {
			return '';
		}
	}
	
	/**
	 * Recursively list the files in the given directory path.
	 * 
	 * @param string $path The directory path on this filesystem
	 * 
	 * @return File[] 
	 */
	public function getFiles($path = '') {
		$keys = $this->gaufrette->listKeys($this->getGaufrettePath($path));
		
		$filesystem = $this;
		return array_map(function($path) use ($filesystem) {
			return new File($filesystem, $path);
		}, $keys['keys']);
	}

	/**
	 * Get a path suitable for passing to the underlying gaufrette filesystem.
	 * 
	 * @param string $path The path relative to this filesystem.
	 * 
	 * @return string
	 */
	private function getGaufrettePath($path) {
		return $this->normalize("$this->chroot/$path");
	}
	
	/**
	 * Get the absolute path to the given filesystem-relative path.
	 * 
	 * @param string $path A file/directory path within this filesystem.
	 * 
	 * @return string
	 */
	private function getFullPath($path = '') {
		$gaufrettePath = $this->normalize($this->getGaufrettePath($path));
		return "$this->localPath/$gaufrettePath";
	}
	
	
	/**
	 * Do a PHP include of the file and return the result.
	 * This only really works with local filesystems amirite?
	 * 
	 * @param string $path Filesystem-relative path for the file to include.
	 * 
	 * @return mixed
	 */
	public function includeFile($path) {
		return include $this->getFullPath($path);
	}
	
	/**
	 * Whether there is a file or directory at the given path.
	 * 
	 * @param string $path Filesystem-relative path to check for existence.
	 * 
	 * @return boolean
	 */
	private function exists($path) {
		return $this->gaufrette->has($this->getGaufrettePath($path));
	}
	
	/**
	 * Write a file, overwriting if necessary.
	 * 
	 * @param string $path    The path to the file.
	 * @param string $content The literal text content of the file.
	 * 
	 * @return void
	 */
	public function put($path, $content) {
		$this->gaufrette->write($this->getGaufrettePath($path), $content, true);
	}
	
	/**
	 * Returns a new filesystem with access limited to the given directory.
	 * 
	 * @param string $path The path relative to this filesystem to chroot to.
	 * 
	 * @return Filesystem A new filesystem instance.
	 */
	public function chroot($path) {
		return new Filesystem($this->gaufrette, $this->localPath, $this->getGaufrettePath($path));
	}
	
	
	/**
	 * Get a standardized form of the given path to work with internally.
	 * 
	 * @param string $path A relative path within this filesystem
	 * 
	 * @return string
	 */
	private function normalize($path) {
		return trim($path, "/");
	}
	
	/**
	 * Shorthand for generating a new local filesystem.
	 * 
	 * @param string $path absolute path to directory on local filesystem.
	 * 
	 * @return Filesystem
	 */
	public static function createLocal($path) {
		return new Filesystem(new Gaufrette(new Local($path)), $path);
	}
	
	/**
	 * Shorthand for generating a new in-memory-only filesystem.
	 * 
	 * @return Filesystem
	 */
	public static function createInMemory() {
		return new Filesystem(new Gaufrette(new InMemory()));
	}
}
