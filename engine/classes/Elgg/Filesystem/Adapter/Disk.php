<?php
namespace Elgg\Filesystem\Adapter;
use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as GaufretteLocal;
use Elgg\EntityDirLocator;

/**
 * Data storage on a local disk
 */
class Disk implements Adapter {
	/**
	 * @var string
	 */
	private $path;
	
	/**
	 * @var Filesystem
	 */
	private $fs;
	
	/**
	 * @var bool
	 */
	private $createDir;
	
	/**
	 * Create a new instance of a local filesystem
	 * 
	 * @todo can't pass GaufretteLocal directly because it doesn't expose path.
	 * 
	 * @param string $path       The current path
	 * @param bool   $create_dir Should directories be created if they don't exist
	 */
	public function __construct($path, $create_dir = false) {
		$this->path = $path;
		$this->createDir = $create_dir;

		$adapter = new GaufretteLocal($path, $create_dir);
		$this->fs = new Filesystem($adapter);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function file($name) {
		return new File($this->fullPath($name), $this->fs);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function directory($path) {
		return new self($this->fullPath($path), $this->createDir);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function fullPath($path) {
		return str_replace("//", "/", "{$this->path}/$path");
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getPathPrefix($guid) {
		$locator = new EntityDirLocator($guid);
		return "$locator";
	}
}