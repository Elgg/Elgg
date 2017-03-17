<?php
/**
 * Iterate over elements in a specific priority.
 *
 * $pl = new \ElggPriorityList();
 * $pl->add('Element 0');
 * $pl->add('Element 10', 10);
 * $pl->add('Element -10', -10);
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
 * Collisions on priority are handled by inserting the element at or as close to the
 * requested priority as possible:
 *
 * $pl = new \ElggPriorityList();
 * $pl->add('Element 5', 5);
 * $pl->add('Colliding element 5', 5);
 * $pl->add('Another colliding element 5', 5);
 *
 * foreach ($pl as $priority => $element) {
 *	var_dump("$priority => $element");
 * }
 *
 * Yields:
 *	5 => 'Element 5',
 *	6 => 'Colliding element 5',
 *	7 => 'Another colliding element 5'
 *
 * You can do priority lookups by element:
 *
 * $pl = new \ElggPriorityList();
 * $pl->add('Element 0');
 * $pl->add('Element -5', -5);
 * $pl->add('Element 10', 10);
 * $pl->add('Element -10', -10);
 *
 * $priority = $pl->getPriority('Element -5');
 *
 * Or element lookups by priority.
 * $element = $pl->getElement(-5);
 *
 * To remove elements, pass the element.
 * $pl->remove('Element -10');
 *
 * To check if an element exists:
 * $pl->contains('Element -5');
 *
 * To move an element:
 * $pl->move('Element -5', -3);
 *
 * \ElggPriorityList only tracks priority. No checking is done in \ElggPriorityList for duplicates or
 * updating. If you need to track this use objects and an external map:
 *
 * function elgg_register_something($id, $display_name, $location, $priority = 500) {
 *	// $id => $element.
 *	static $map = array();
 *	static $list;
 *
 *	if (!$list) {
 *		$list = new \ElggPriorityList();
 *	}
 *
 *	// update if already registered.
 *	if (isset($map[$id])) {
 *		$element = $map[$id];
 *		// move it first because we have to pass the original element.
 *		if (!$list->move($element, $priority)) {
 *			return false;
 *		}
 *		$element->display_name = $display_name;
 *		$element->location = $location;
 *	} else {
 *		$element = new \stdClass();
 *		$element->display_name = $display_name;
 *		$element->location = $location;
 *		if (!$list->add($element, $priority)) {
 *			return false;
 *		}
 *		$map[$id] = $element;
 *	}
 *
 *	return true;
 * }
 *
 * @package    Elgg.Core
 * @subpackage Helpers
 */
class ElggPriorityList
	implements \Iterator, \Countable {

	/**
	 * The list of elements
	 *
	 * @var array
	 */
	private $elements = [];

	/**
	 * Create a new priority list.
	 *
	 * @param array $elements An optional array of priorities => element
	 */
	public function __construct(array $elements = []) {
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
	 * @param bool  $exact    unused
	 * @return int            The priority of the added element.
	 * @todo remove $exact or implement it. Note we use variable name strict below.
	 */
	public function add($element, $priority = null, $exact = false) {
		if ($priority !== null && !is_numeric($priority)) {
			return false;
		} else {
			$priority = $this->getNextPriority($priority);
		}

		$this->elements[$priority] = $element;
		$this->sorted = false;
		return $priority;
	}

	/**
	 * Removes an element from the list.
	 *
	 * @warning The element must have the same attributes / values. If using $strict, it must have
	 *          the same types. array(10) will fail in strict against array('10') (str vs int).
	 *
	 * @param mixed $element The element to remove from the list
	 * @param bool  $strict  Whether to check the type of the element match
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
	 * Move an existing element to a new priority.
	 *
	 * @param mixed $element      The element to move
	 * @param int   $new_priority The new priority for the element
	 * @param bool  $strict       Whether to check the type of the element match
	 * @return bool
	 */
	public function move($element, $new_priority, $strict = false) {
		$new_priority = (int) $new_priority;
		
		$current_priority = $this->getPriority($element, $strict);
		if ($current_priority === false) {
			return false;
		}

		if ($current_priority == $new_priority) {
			return true;
		}

		// move the actual element so strict operations still work
		$element = $this->getElement($current_priority);
		unset($this->elements[$current_priority]);
		return $this->add($element, $new_priority);
	}

	/**
	 * Returns the elements
	 *
	 * @return array
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
	 * The callback function should accept the array of elements as the first
	 * argument and should return a sorted array.
	 *
	 * This function can be called multiple times.
	 *
	 * @param callback $callback The callback for sorting. Numeric sorting is the default.
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
	 * @warning This can return 0 if the element's priority is 0.
	 *
	 * @param mixed $element The element to check for.
	 * @param bool  $strict  Use strict checking?
	 * @return mixed False if the element doesn't exists, the priority if it does.
	 */
	public function getPriority($element, $strict = false) {
		return array_search($element, $this->elements, $strict);
	}

	/**
	 * Returns the element at $priority.
	 *
	 * @param int $priority The priority
	 * @return mixed The element or false on fail.
	 */
	public function getElement($priority) {
		return (isset($this->elements[$priority])) ? $this->elements[$priority] : false;
	}

	/**
	 * Returns if the list contains $element.
	 *
	 * @param mixed $element The element to check.
	 * @param bool  $strict  Use strict checking?
	 * @return bool
	 */
	public function contains($element, $strict = false) {
		return $this->getPriority($element, $strict) !== false;
	}

	
	/**********************
	 * Interface methods *
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
		return reset($this->elements);
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
		return ($key !== null && $key !== false);
	}

	/**
	 * Countable interface
	 *
	 * @see Countable::count()
	 * @return int
	 */
	public function count() {
		return count($this->elements);
	}
}
