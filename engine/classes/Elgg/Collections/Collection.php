<?php

namespace Elgg\Collections;

use ArrayAccess;
use Countable;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\InvalidArgumentException;
use SeekableIterator;

/**
 * A collection of unique items
 */
class Collection implements CollectionInterface,
							ArrayAccess,
							SeekableIterator,
							Countable {

	/**
	 * @var CollectionItemInterface[]
	 */
	protected $items = [];

	/**
	 * @var string
	 */
	protected $item_class;
	
	/**
	 * @var int positition of the SeekableIterator
	 */
	protected $position;

	/**
	 * Constructor
	 *
	 * @param CollectionItemInterface[] $items      Items
	 * @param string                    $item_class Member class
	 *                                              Restrict members of the collection to instances of this class
	 */
	public function __construct($items = [], $item_class = null) {
		if ($item_class) {
			if (!is_subclass_of($item_class, CollectionItemInterface::class)) {
				throw new InvalidArgumentException('Item class must implement ' . CollectionItemInterface::class);
			}

			$this->item_class = $item_class;
		}

		foreach ($items as $item) {
			$this->add($item);
		}
	}

	/**
	 * Validate if item is a valid collection item
	 *
	 * @param mixed $item Item
	 *
	 * @return void
	 */
	protected function assertValidItem($item) {
		$class = $this->item_class ? : CollectionItemInterface::class;

		if (!$item instanceof $class) {
			throw new InvalidParameterException('Collection ' . __CLASS__ . ' only accepts instances of ' . $class);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		return count($this->items);
	}

	/**
	 * {@inheritdoc}
	 */
	public function add($item) {
		$this->assertValidItem($item);

		$this->items[$item->getID()] = $item;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($id) {
		return elgg_extract($id, $this->items);
	}

	/**
	 * {@inheritdoc}
	 */
	public function has($id) {
		return array_key_exists($id, $this->items);
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove($id) {
		unset($this->items[$id]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function fill($items) {
		$this->items = [];
		foreach ($items as $item) {
			$this->add($item);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function merge($items) {
		foreach ($items as $item) {
			$this->add($item);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function filter(callable $callback = null) {
		if ($callback) {
			$items = array_filter($this->items, $callback);
		} else {
			$items = array_values($this->items);
		}

		return new static($items, $this->item_class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function sort(callable $callback = null) {
		if (!$callback) {
			$callback = function (CollectionItemInterface $f1, CollectionItemInterface $f2) {
				$p1 = $f1->getPriority() ? : 500;
				$p2 = $f2->getPriority() ? : 500;
				if ($p1 === $p2) {
					return 0;
				}

				return $p1 < $p2 ? -1 : 1;
			};
		}

		uasort($this->items, $callback);

		return $this;
	}

	/**
	 * Walk through members of the collection and apply a callback
	 *
	 * Unlike CollectionInterface::map(), this method does not return the result of mapping,
	 * rather returns the exact same instance of the collection after walking through
	 * its members by reference
	 *
	 * @see CollectionInterface::map()
	 *
	 * @param callable $callback Callback function
	 *
	 * @return static
	 */
	public function walk(callable $callback) {
		foreach ($this->items as $id => $item) {
			call_user_func($callback, $item, $id);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function map(callable $callback) {
		$map = [];

		$items = $this->filter()->all();
		foreach ($items as $id => &$item) {
			$map[$id] = call_user_func($callback, $item, $id);
		}

		return $map;
	}

	/**
	 * ArrayAccess interface functions
	 */
	
	/**
	 * {@inheritdoc}
	 */
	public function offsetExists($offset) {
		return $this->has($offset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetSet($offset, $value) {
		$this->assertValidItem($value);

		$key = $value->getID();
		$this->items[$key] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetUnset($offset) {
		unset($this->items[$offset]);
	}

	/**
	 * SeekableIterator interface functions
	 */
	
	/**
	 * {@inheritdoc}
	 */
	public function current() {
		$keys = array_keys($this->items);
		
		return $this->get($keys[$this->position]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function next() {
		$this->position++;
	}

	/**
	 * {@inheritdoc}
	 */
	public function key() {
		$keys = array_keys($this->items);
		
		return $keys[$this->position];
	}

	/**
	 * {@inheritdoc}
	 */
	public function valid() {
		$keys = array_keys($this->items);
		
		return isset($keys[$this->position]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind() {
		$this->position = 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function seek($position) {
		$keys = array_keys($this->items);

		if (!isset($keys[$position])) {
			throw new \OutOfBoundsException();
		}

		$this->position = $position;
	}
}
