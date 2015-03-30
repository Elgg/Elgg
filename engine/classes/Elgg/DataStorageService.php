<?php
namespace Elgg;
use Elgg\Filesystem\Adapter\Adapter;

class DataStorageService {
	private $logger;
	private $adapter;
	
	// @todo file/dir object cache.
	
	
	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}
	
	public function setAdapter(Adapter $adapter) {
		$this->adapter = $adapter;
		return $this;
	}
	
	public function hasAdapter() {
		return isset($this->adapter);
	}
	
	public function file($name) {
		if (!$this->hasAdapter()) {
			throw new Di\MissingValueException("Filesystem adapter is not set");
		}
		
		return $this->adapter->file($name);
	}
	
	public function directory($path) {
		if (!$this->hasAdapter()) {
			throw new Di\MissingValueException("Filesystem adapter is not set");
		}
		
		return $this->adapter->directory($path);
	}
}