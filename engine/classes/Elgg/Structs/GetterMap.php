<?php

namespace Elgg\Structs;

/**
 * Uses a client-provided getter function to map from keys to values.
 * 
 * @since 2.0.0
 * @access private
 */
class GetterMap/*<K,V>*/ implements Map/*<K,V>*/ {
	/**
	 * Constructor
	 * 
	 * @param Collection<K>   $keys   List of available keys
	 * @param callable<[K],V> $getter Takes a key and returns a value
	 */
	public function __construct(Collection/*<K>*/ $keys, callable/*<[K],V>*/ $getter) {
		$this->keys = $keys;
		$this->getter = $getter;
	}
	
	/** @inheritDoc */
	public function get(/*K*/ $key) {
		$getter = $this->getter;
		return $getter($key);
	}

	/** @inheritDoc */
	public function has(/*K*/ $key) {
		return $this->get($key) !== null;
	}
	
	/** @inheritDoc */
	public function keys() {
		return $this->keys->filter([$this, 'has']);
	}
	
	/** @inheritDoc */
	public function values() {
		return $this->keys()->map([$this, 'get']);
	}
}