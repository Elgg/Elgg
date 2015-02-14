<?php
namespace Elgg\Filesystem;

use Elgg\Structs\ArrayCollection;
use Gaufrette\Adapter\Local;
use Gaufrette\Adapter\InMemory;
use Gaufrette\Filesystem as Gaufrette;

/**
 * A wrapper around Gaufrette that implements Elgg's filesystem API.
 * 
 * @since 1.10.0
 * 
 * @access private
 */
final class GaufretteDirectory implements Directory {
	
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
	
	/** @inheritDoc */
	public function chroot($path) {
		return new self($this->gaufrette, $this->localPath, $this->getGaufrettePath($path));
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
	
	/** @inheritDoc */
	public function isFile($path) {
		return !$this->isDirectory($path) &&
			$this->gaufrette->has($this->getGaufrettePath($path));
	}
	
	/** @inheritDoc */
	public function getContents($path) {
		try {
			return $this->gaufrette->read($this->getGaufrettePath($path));
		} catch (\Exception $e) {
			return '';
		}
	}
	
	/** @inheritDoc */
	public function getFile($path) {
		if ($this->isDirectory($path)) {
			throw new \RuntimeException("There is already a directory at that location: $path");
		}
		
		return new File($this, $path);
	}
	
	/** @inheritDoc */
	public function getFiles($path = '') {
		$keys = $this->gaufrette->listKeys($this->getGaufrettePath($path));
		
		$files = new ArrayCollection($keys['keys']);
		
		return $files->map(function($path) {
			return new File($this, $path);
		});
	}

	/**
	 * Get the absolute path to the given directory-relative path.
	 * 
	 * @param string $path A file/directory path within this directory.
	 * 
	 * @return string
	 */
	private function getFullPath($path = '') {
		$gaufrettePath = $this->normalize($this->getGaufrettePath($path));
		return "$this->localPath/$gaufrettePath";
	}
	
	/**
	 * Get a path suitable for passing to the underlying gaufrette filesystem.
	 * 
	 * @param string $path The path relative to this directory.
	 * 
	 * @return string
	 */
	private function getGaufrettePath($path) {
		return $this->normalize("$this->chroot/$path");
	}
	
	/** @inheritDoc */
	public function includeFile($path) {
		return include $this->getFullPath($path);
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
	
	/** @inheritDoc */
	public function putContents($path, $content) {
		$this->gaufrette->write($this->getGaufrettePath($path), $content, true);
	}
	
	/**
	 * Shorthand for generating a new local filesystem.
	 * 
	 * @param string $path absolute path to directory on local filesystem.
	 * 
	 * @return Filesystem
	 */
	public static function createLocal($path) {
		return new self(new Gaufrette(new Local($path)), $path);
	}
	
	/**
	 * Shorthand for generating a new in-memory-only filesystem.
	 * 
	 * @return Filesystem
	 */
	public static function createInMemory() {
		return new self(new Gaufrette(new InMemory()));
	}
}
