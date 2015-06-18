<?php

namespace Elgg\Structs;

/**
 * A single key-value pair for use in a Map structure.
 * 
 * @since 2.0.0
 * @access private
 */
class MapEntry/*<K,V>*/ {
	/** @var K */
	public $key;
	
	/** @var V */
	public $val;
	
	/**
	 * Contructor
	 * 
	 * @param K $key The key
	 * @param V $val The value
	 */
	public function __construct($key, $val) {
		$this->key = $key;
		$this->val = $val;
	}
}