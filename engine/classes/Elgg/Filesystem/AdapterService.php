<?php
namespace Elgg\Filesystem;
use Elgg\Filesystem\Adapter\Adapter;


class AdapterService {
	private $logger;
	
	/**
	 *
	 * @var string
	 */
	private $default;
	
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
	
	public function setDefault($name) {
		if ($this->default) {
			throw new \UnexpectedValueException("There is already a default filesystem adapter.");
		}
		// @todo allow unregistered adapters?
		$adapter = $this->get($name);
		$this->default = $adapter;
		return true;
	}
	
	public function getDefault() {
		if (!$this->default instanceof Adapter) {
			throw new \UnexpectedValueException("Default filesystem adapter is not set.");
		}
		return $this->default;
	}
	
	public static function buildFromParams(array $params) {
		// build and return a new adapter from a set of param
		// expects an array with classname and adapter-specific params
		if (!isset($params['classname'])) {
			throw new \InvalidArgumentException("Cannot build a filesystem adapter without a class name");
		}
		
		if (!class_exists($params['classname'])) {
			throw new \InvalidArgumentException("Filesystem adapter \"{$params['classname']}\" does not exist");
		}
		
		return new $params['classname']($params);
	}
}