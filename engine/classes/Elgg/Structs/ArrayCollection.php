<?php
namespace Elgg\Structs;

/**
 * Uses a native PHP array to implement the Collection interface.
 * 
 * @since 1.10
 * @access private
 */
final class ArrayCollection implements Collection {
	/** @var array */
	private $items;
	
	/**
	 * Constructor
	 * 
	 * @param array $items The set of items in the collection
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
		
		foreach ($this->items as $item) {
			if ($filter($item)) {
				$results[] = $item;
			}
		}
		
		return new ArrayCollection($results);
	}
	
	/** @inheritDoc */
	public function key() {
		return key($this->items);
	}
	
	/** @inheritDoc */
	public function map(callable $mapper) {
		$results = array();
		foreach ($this->items as $item) {
			$results[] = $mapper($item);
		}
		return new ArrayCollection($results);
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
	public function touch(callable $callback) {
		$callback($this);
		return $this;
	}
	
	/** @inheritDoc */
	public function valid() {
		return key($this->items) !== NULL;
	}
	
	/** @inheritDoc */
	public function unique() {
		$uniques = [];
		
		foreach ($this->items as $item) {
			if (!in_array($item, $uniques, true)) {
				$uniques[] = $item;
			}
		}
		
		return new self($uniques);
	}
}