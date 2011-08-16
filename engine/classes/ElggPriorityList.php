<?php
/**
 * Iterate over elements in a specific priority.
 *
 * You can add, remove, and access elements using OOP or array interfaces:
 *
 * // OOP
 * $pl = new ElggPriorityList();
 * $pl->add('Element 0');
 * $pl->add('Element -5', -5);
 * $pl->add('Element 10', 10);
 * $pl->add('Element -10', -10);
 *
 * $pl->remove('Element -5');
 *
 * $elements = $pl->getElements();
 * var_dump($elements);
 *
 * Yields:
 *
 * array(
 *	-10 => 'Element -10',
 * 	0 => 'Element 0',
 * 	10 => 'Element 10',
 * )
 *
 *
 * // Array
 * $pl = new ElggPriorityList();
 * $pl[] = 'Element 0';
 * $pl[-5] = 'Element -5';
 * $pl[10] = 'Element 10';
 * $pl[-10] = 'Element -10';
 *
 * $priority = $pl->getPriority('Element -5');
 * unset($pl[$priority]);
 *
 * foreach ($pl as $priority => $element) {
 *	var_dump("$priority => $element");
 * }
 *
 * Yields:
 * -10 => Element -10
 * 0 => Element 0
 * 10 => Element 10
 *
 *
 * Collisions with priority are handled by inserting the element as close to the requested priority
 * as possible.
 *
 * $pl = new ElggPriorityList();
 * $pl[5] = 'Element 5';
 * $pl[5] = 'Colliding element 5';
 * $pl[5] = 'Another colliding element 5';
 *
 * var_dump($pl->getElements());
 *
 * Yields:
 *
 * array(
 *	5 => 'Element 5',
 *	6 => 'Colliding element 5',
 *	7 => 'Another colliding element 5'
 * )
 *
 * @package Elgg.Core
 * @subpackage Helpers
 */

class ElggPriorityList
	implements Iterator, ArrayAccess, Countable {

	/**
	 * The list of elements
	 *
	 * @var array
	 */
	private $elements = array();

	/**
	 * Create a new priority list.
	 *
	 * @param array $elements An optional array of priorities => element
	 */
	public function __construct(array $elements = array()) {
		if ($elements) {
			foreach ($elements as $priority => $element) {
				$this->add($element, $priority);
			}
		}
	}

	/**
	 * Adds an element to the list.
	 *
	 * @warning This returns the priority at which the element was added, which can be 0. Use
	 *          !== false to check for success.
	 *
	 * @param mixed $element  The element to add to the list.
	 * @param mixed $priority Priority to add the element. In priority collisions, the original element
	 *                        maintains its priority and the new element is to the next available
	 *                        slot, taking into consideration all previously registered elements.
	 *                        Negative elements are accepted.
	 * @return int            The priority the element was added at.
	 */
	public function add($element, $priority = null) {
		if ($priority !== null && !is_numeric($priority)) {
			return false;
		} else {
			$priority = $this->getNextPriority($priority);
		}

		$this->elements[$priority] = $element;
		return $priority;
	}

	/**
	 * Removes an element from the list.
	 *
	 * @warning The element must have the same attributes / values. If using $strict, it must have
	 *          the same types. array(10) will fail in strict against array('10') (str vs int).
	 *
	 * @param type $element
	 * @return bool
	 */
	public function remove($element, $strict = false) {
		$index = array_search($element, $this->elements, $strict);
		if ($index !== false) {
			unset($this->elements[$index]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns the elements
	 *
	 * @param type $elements
	 * @param type $sort
	 */
	public function getElements() {
		$this->sortIfUnsorted();
		return $this->elements;
	}

	/**
	 * Sort the elements optionally by a callback function.
	 *
	 * If no user function is provided the elements are sorted by priority registered.
	 *
	 * The callback function should accept the array of elements as the first argument and should
	 * return a sorted array.
	 *
	 * This function can be called multiple times.
	 *
	 * @param type $callback
	 * @return bool
	 */
	public function sort($callback = null) {
		if (!$callback) {
			ksort($this->elements, SORT_NUMERIC);
		} else {
			$sorted = call_user_func($callback, $this->elements);

			if (!$sorted) {
				return false;
			}

			$this->elements = $sorted;
		}
		
		$this->sorted = true;
		return true;
	}

	/**
	 * Sort the elements if they haven't been sorted yet.
	 *
	 * @return bool
	 */
	private function sortIfUnsorted() {
		if (!$this->sorted) {
			return $this->sort();
		}
	}

	/**
	 * Returns the next priority available.
	 *
	 * @param int $near Make the priority as close to $near as possible.
	 * @return int
	 */
	public function getNextPriority($near = 0) {
		$near = (int) $near;
		
		while (array_key_exists($near, $this->elements)) {
			$near++;
		}

		return $near;
	}


	/**
	 * Returns the priority of an element if it exists in the list.
	 * 
	 * @warning This can return 0 if the element's priority is 0. Use identical operator (===) to
	 * check for false if you want to know if an element exists.
	 *
	 * @param mixed $element
	 * @return mixed False if the element doesn't exists, the priority if it does.
	 */
	public function getPriority($element, $strict = false) {
		return array_search($element, $this->elements, $strict);
	}

	/**********************
	 * Interfaces methods *
	 **********************/


	/**
	 * Iterator
	 */

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::rewind()
	 * @return void
	 */
	public function rewind() {
		$this->sortIfUnsorted();
		return rewind($this->elements);
	}

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::current()
	 * @return mixed
	 */
	public function current() {
		$this->sortIfUnsorted();
		return current($this->elements);
	}

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::key()
	 * @return int
	 */
	public function key() {
		$this->sortIfUnsorted();
		return key($this->elements);
	}

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::next()
	 * @return mixed
	 */
	public function next() {
		$this->sortIfUnsorted();
		return next($this->elements);
	}

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		$this->sortIfUnsorted();
		$key = key($this->elements);
		return ($key !== NULL && $key !== FALSE);
	}

	// Coutable
	public function count() {
		return count($this->elements);
	}

	// ArrayAccess
	public function offsetExists($offset) {
		return isset($this->elements[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->elements[$offset]) ? $this->elements[$offset] : null;
	}

	public function offsetSet($offset, $value) {
		return $this->add($value, $offset);
	}

	public function offsetUnset($offset) {
		if (isset($this->elements[$offset])) {
			unset($this->elements[$offset]);
		}
	}
}