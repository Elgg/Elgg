<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\DeprecationWrapperUnitTest
 */
class DeprecationWrapperTestObj2 extends \ArrayObject {
	
	public $data = array();
	
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}
	
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}
	
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}
	
	public function offsetGet($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}
}
