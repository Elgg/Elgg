<?php
namespace Elgg\Filesystem;
use Gaufrette\Filesystem;
use Gaufrette\Adapter;

/**
 * Provides an API for adding data storage adapters.
 */
class StorageAdapterService {
	private $logger;
	
	/**
	 *
	 * @var Filesystem[]
	 */
	private $filesystems;
	
	/**
	 * Constructor
	 * 
	 * @param \Elgg\Logger $logger
	 */
	public function __construct(\Elgg\Logger $logger) {
		$this->logger = $logger;
	}
	
	/**
	 * Registers a Filesystem
	 * 
	 * @param string  $name      The name of the filesystem
	 * @param Adapter $adapter   The Gaufrette adapter
	 * @param bool    $canStream Can this adapter stream content?
	 * 
	 * @return boolean
	 * @throws \UnexpectedValueException
	 */
	public function set($name, Adapter $adapter, $canStream = true) {
		if ($this->has($name)) {
			throw new \UnexpectedValueException("Filesystem adapter \"$name\" already exists.");
		}
		$fs = new Filesystem($adapter);
		$fs->canStream = $canStream;
		$this->filesystems[$name] = $fs;
		
		return true;
	}
	
	/**
	 * Get a named Filesystem.
	 * 
	 * @param string $name The name of the filesystem
	 * @return Filesystem
	 * @throws \InvalidArgumentException
	 */
	public function get($name) {
		if (!$this->has($name)) {
			throw new \InvalidArgumentException("Filesystem \"$name\" does not exist.");
		}
		
		return $this->filesystems[$name];
	}
	
	/**
	 * Check if an adapter exists
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function has($name) {
		return isset($this->filesystems[$name]);
	}
	
	/**
	 * Remove an adapter
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public function remove($name) {
		// throw if doesn't exist
		$this->get($name);
		unset($this->filesystems[$name]);
		return true;
	}
	
	/**
	 * List registered adapters
	 * 
	 * @return type
	 */
	public function listAdapters() {
		return array_keys($this->filesystems);
	}
}