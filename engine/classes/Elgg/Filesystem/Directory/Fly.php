<?php

namespace Elgg\Filesystem\Directory;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Filesystem\Directory;
use Elgg\Filesystem\File;
use Elgg\Structs\Collection;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

/**
 * A wrapper around Flysystem that implements Elgg's filesystem API.
 *
 * @since 1.10.0
 * @internal
 */
final class Fly implements Directory {

	/**
	 * @var Filesystem
	 */
	private $fs;

	/**
	 * @var string
	 */
	private $local_path;

	/**
	 * @var string Path relative to the filesystem's root
	 */
	private $chroot;

	/**
	 * Use one of the static factory functions to create an instance.
	 *
	 * @param Filesystem $filesystem The underlying filesystem implementation. It must have the 'ListFiles' plugin.
	 * @param string     $local_path Only applicable for local filesystem.
	 * @param string     $chroot     Path relative to the underlying filesystem root.
	 */
	public function __construct(Filesystem $filesystem, $local_path = '', $chroot = '') {
		$this->fs = $filesystem;
		$this->local_path = rtrim(strtr($local_path, '\\', '/'), "/\\");
		$this->chroot = $this->normalize($chroot);
	}

	/**
	 * {@inheritDoc}
	 */
	public function chroot($path) {
		return new self($this->fs, $this->local_path, $path);
	}

	/**
	 * Whether this filesystem has an existing directory at the given path.
	 *
	 * @param string $path The path to the directory, relative to this filesystem.
	 *
	 * @return boolean
	 */
	private function isDirectory($path) {
		$path = $this->getInternalPath($path);
		return $this->fs->has($path) && $this->fs->get($path)->isDir();
	}

	/**
	 * {@inheritDoc}
	 */
	public function isFile($path) {
		$path = $this->getInternalPath($path);
		return $this->fs->has($path) && $this->fs->get($path)->isFile();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getContents($path) {
		return (string) $this->fs->read($this->getInternalPath($path));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFile($path) {
		if ($this->isDirectory($path)) {
			throw new \RuntimeException("There is already a directory at that location: $path");
		}

		return new File($this, $path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFiles($path = '', $recursive = true) {
		return $this->getEntries($path, $recursive, ['file']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDirectories($path = '', $recursive = true) {
		return $this->getEntries($path, $recursive, ['dir']);
	}

	/**
	 * List the files and directories in the given directory path.
	 *
	 * @param string   $path      The subdirectory path within this directory
	 * @param bool     $recursive Find files and directories recursively
	 * @param string[] $types     Entry types to return ('file' and/or 'dir')
	 *
	 * @return Collection<File|Directory>
	 */
	protected function getEntries($path = '', $recursive = true, $types = ['file', 'dir']) {
		$contents = $this->fs->listContents($this->getInternalPath($path), $recursive);
		if (empty($contents)) {
			$contents = [];
		}

		$contents = array_filter($contents, function ($metadata) use ($types) {
			return in_array($metadata['type'], $types);
		});

		return Collection\InMemory::fromArray(array_map(function ($metadata) {
			if ($metadata['type'] === 'file') {
				return new File($this, $metadata['path']);
			}

			return new self($this->fs, $this->local_path, $metadata['path']);
		}, $contents));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPath($path = '') {
		$path = $this->normalize($this->getInternalPath($path));
		return "{$this->local_path}/$path";
	}

	/**
	 * Get a path suitable for passing to the underlying filesystem.
	 *
	 * @param string $path The path relative to this directory.
	 *
	 * @return string
	 */
	private function getInternalPath($path) {
		$path = strtr($path, '\\', '//');
		return $this->normalize("{$this->chroot}/$path");
	}

	/**
	 * {@inheritDoc}
	 */
	public function includeFile($path) {
		return include $this->getPath($path);
	}

	/**
	 * {@inheritDoc}
	 */
	public function putContents($path, $content) {
		$this->fs->put($this->getInternalPath($path), $content);
	}

	/**
	 * Shorthand for generating a new local filesystem.
	 *
	 * @param string $path absolute path to directory on local filesystem.
	 *
	 * @return Directory
	 */
	public static function createLocal($path) {
		$fs = new Filesystem(new LocalAdapter($path));
		return new self($fs, $path);
	}

	/**
	 * Shorthand for generating a new in-memory-only filesystem.
	 *
	 * @return Directory
	 */
	public static function createInMemory() {
		$fs = new Filesystem(new MemoryAdapter());
		return new self($fs);
	}

	/**
	 * Get a standardized form of the given path to work with internally.
	 *
	 * @param string $path A relative path within this filesystem
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 */
	private function normalize($path) {

		$test_path = "/$path/";
		if (strpos($test_path, '/./') !== false || strpos($test_path, '/../') !== false) {
			throw new InvalidArgumentException('Paths cannot contain "." or ".."');
		}

		return trim(strtr($path, '\\', '/'), "/");
	}
}
