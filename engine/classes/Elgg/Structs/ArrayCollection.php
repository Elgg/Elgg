<?php
namespace Elgg\Structs;

use ArrayIterator;

/**
 * An immutable collection implemented using native PHP arrays.
 *
 * @access private
 */
final class ArrayCollection implements Collection {

	/* @var array */
	private $items;
	
	/**
	 * Constructor
	 * 
	 * @param array $items The initial collection of items in the collection.
	 */
	public function __construct(array $items = []) {
		$this->items = array_values($items);
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
	public function filter(callable $filter) {
		return new self(array_filter($this->items, $filter));
	}
	
	/** @inheritDoc */
	public function getIterator() {
		return new ArrayIterator($this->toArray());
	}
	
	/** @inheritDoc */
	public function isEmpty() {
		return count($this) > 0;
	}
	
	/** @inheritDoc */
	public function map(callable $mapper) {
		$items = [];
		
		foreach ($this->items as $item) {
			$items[] = $mapper($item);
		}
		
		return new self($items);
	}
	
	/** @inheritDoc */
	public function toArray() {
		return $this->items;
	}
	
	/** @inheritDoc */
	public function where(array $options) {
		// TODO(ewinslow): Actual implementation plz...
		return $this;
	}
}

