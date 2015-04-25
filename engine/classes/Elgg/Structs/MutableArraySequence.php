<?php
namespace Elgg\Structs;

use ArrayIterator;

/**
 * A MutableSequence implemented using native PHP arrays.
 *
 * @access private
 */
final class MutableArraySequence implements MutableSequence {

	/* @var array */
	private $items;
	
	/**
	 * Create a new sequence
	 */
	public function __construct(array $items = []) {
		$this->items = $items;
	}
	
	/** @inheritDoc */
	public function add($item) {
		$this->push($item);
	}
	
	/** @inheritDoc */
	public function clear() {
		$this->items = [];
	}
	
	/** @inheritDoc */
	public function contains($item) {
		return $this->indexOf($item) != -1;
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
		return new ArraySequence(array_filter($this->items, $filter));
	}
	
	/** @inheritDoc */
	public function first() {
		return $this->items[0];
	}
	
	/** @inheritDoc */
	public function indexOf($item) {
		$index = array_search($item, $this->items, true);
		return $index === FALSE ? -1 : $index;
	}
	
	/** @inheritDoc */
	public function isEmpty() {
		return count($this) == 0;
	}
	
	/** @inheritDoc */
	public function insertAt($index, $item) {
		$this->splice($index, 0, [$item]);
	}
	
	/** @inheritDoc */
	public function getIterator() {
		return new ArrayIterator($this->toArray());
	}
	
	/** @inheritDoc */
	public function last() {
		return $this->items[count($this) - 1];
	}
	
	/** @inheritDoc */
	public function map(callable $mapper) {
		$items = [];
		
		foreach ($this->items as $item) {
			$items[] = $mapper($item);
		}
		
		return $items;
	}

	/** @inheritDoc */
	public function next() {
		return next($this->items);
	}
	
	/** @inheritDoc */
	public function peek() {
		return $this->last();
	}

	/** @inheritDoc */
	public function pop() {
		return array_pop($this->items);
	}

	/** @inheritDoc */
	public function push($item) {
		array_push($this->items, $item);
	}
	
	/** @inheritDoc */
	public function remove($item) {
		$index = $this->indexOf($item);
		if ($index === -1) {
			throw new \Exception("Item was not found in this sequence");
		}
		
		$this->removeAt($index);
	}
	
	/** @inheritDoc */
	public function removeAt($index) {
		return $this->splice($index, 1)->first();
	}
	
	/** @inheritDoc */
	public function rewind() {
		return rewind($this->items);
	}

	/** @inheritDoc */
	public function shift() {
		return array_shift($this->items);
	}
	
	/** @inheritDoc */
	public function slice($index = 0, $limit = 0) {
		return new ArraySequence(array_slice($this->items, $index, $limit));
	}
	
	/** @inheritDoc */
	public function splice($index, $limit = 0, array $replacements = []) {
		return new ArraySequence(array_splice($this->items, $index, $limit, $replacements));
	}
	
	/** @inheritDoc */
	public function toArray() {
		return $this->items;
	}

	/** @inheritDoc */
	public function unshift($item) {
		array_unshift($this->items, $item);
	}
	
	/** @inheritDoc */
	public function valid() {
		return key($this) < count($this);
	}

	/** @inheritDoc */
	public function where(array $options) {
		// TODO(ewinslow): Actual implementation plz...
		return $this;
	}
}

