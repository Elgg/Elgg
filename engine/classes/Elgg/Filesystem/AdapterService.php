<?php
namespace Elgg\Filesystem;
use Elgg\Filesystem\Adapter\Adapter;


class AdapterService {
	private $logger;
	
	/**
	 *
	 * @var Adapter[]
	 */
	private $adapters;
	
	public function __construct(\Elgg\Logger $logger) {
		$this->logger = $logger;
	}
	
	public function set($name, Adapter $adapter, $default = false) {
		if ($this->has($name)) {
			throw new \UnexpectedValueException("Filesystem adapter \"$name\" already exists.");
		}
		$this->adapters[$name] = $adapter;
		
		if ($default) {
			$this->setDefault($name);
		}
		return true;
	}
	
	/**
	 * 
	 * @param type $name
	 * @return Adapter
	 * @throws \InvalidArgumentException
	 */
	public function get($name) {
		if (!$this->has($name)) {
			throw new \InvalidArgumentException("Filesystem Adapter \"$name\" does not exist.");
		}
		
		return $this->adapters[$name];
	}
	
	public function has($name) {
		return isset($this->adapters[$name]);
	}
	
	public function remove($name) {
		// throw if doesn't exist
		$this->get($name);
		unset($this->adapters[$name]);
		return true;
	}
	
	public function listAdapters() {
		return array_keys($this->adapters);
	}
}