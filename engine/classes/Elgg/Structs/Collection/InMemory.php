<?php

namespace Elgg\Structs\Collection;

use Elgg\Structs\Collection;

/**
 * Uses native PHP array to implement the Collection interface.
 *
 * @since 1.10
 * @internal
 */
final class InMemory implements Collection {
	
	/**
	 * Constructor
	 *
	 * @param array $items The set of items in the collection
	 */
	private function __construct(protected array $items = []) {
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function contains($item) {
		return in_array($item, $this->items, true);
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function count() {
		return count($this->items);
	}
	
	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		return current($this->items);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function filter(callable $filter) {
		$results = [];
		
		foreach ($this->items as $item) {
			if ($filter($item)) {
				$results[] = $item;
			}
		}
		
		return new self($results);
	}
	
	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function key() {
		return key($this->items);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function map(callable $mapper) {
		$results = [];
		foreach ($this->items as $item) {
			$results[] = $mapper($item);
		}
		
		return self::fromArray($results);
	}
	
	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function next() {
		return next($this->items);
	}
	
	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function rewind() {
		reset($this->items);
	}
	
	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function valid() {
		return key($this->items) !== null;
	}
	
	/**
	 * Factory function for converting from an array to a ton of items.
	 *
	 * @param array $items The list of objects to include. Generics come later.
	 *
	 * @return self
	 */
	public static function fromArray(array $items) {
		return new self($items);
	}
}
