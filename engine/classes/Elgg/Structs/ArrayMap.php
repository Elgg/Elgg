<?php
namespace Elgg\Structs;

/**
 * Uses native PHP array to implement the Collection interface.
 * 
 * @package    Elgg.Core
 * @subpackage Structs
 * @since      1.10
 *
 * @access private
 */
final class ArrayMap implements Map {
	/** @var array */
	private $items;
	
	/**
	 * Constructor
	 * 
	 * @param array $items The set of key-value pairs in the map
	 */
	public function __construct(array $items = array()) {
		$this->items = $items;
	}
	
	/** @inheritDoc */
	public function contains($item) {
		return in_array($item, $this->items, true);
	}

	/** @inheritDoc */
	public function count() {
		return count($this->items);
	}
	
	/** @inheritDoc */
	public function current() {
		return current($this->items);
	}
	
	/** @inheritDoc */
	public function filter(callable $filter) {
		$results = array();
		
		foreach ($this->items as $key => $item) {
			if (call_user_func($filter, $item, $key)) {
				$results[$key] = $item;
			}
		}
		
		return new ArrayMap($results);
	}
	
	/** @inheritDoc */
	public function join($glue) {
		return implode($glue, $this->items);
	}
	
	/** @inheritDoc */
	public function key() {
		return key($this->items);
	}
	
	/** @inheritDoc */
	public function map(callable $mapper) {
		$results = array();
		
		foreach ($this->items as $key => $item) {
			$results[$key] = call_user_func($mapper, $item, $key);
		}
		
		return new ArrayMap($results);
	}
	
	/** @inheritDoc */
	public function next() {
		return next($this->items);
	}
	
	/** @inheritDoc */
	public function rewind() {
		reset($this->items);
	}
	
	/** @inheritDoc */
	public function valid() {
		return key($this->items) !== NULL;
	}
}