<?php
abstract class ElggData implements
	Iterator,	// Override foreach behaviour
	ArrayAccess // Override for array access
{
	/**
	 * The main attributes of an entity.
	 * Holds attributes to save to database
	 * This contains the site's main properties (id, etc)
	 * Blank entries for all database fields should be created by the constructor.
	 * Subclasses should add to this in their constructors.
	 * Any field not appearing in this will be viewed as a
	 */
	protected $attributes;
	
	/*
	 * ITERATOR INTERFACE
	 */

	/*
	 * This lets an entity's attributes be displayed using foreach as a normal array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */
	protected $valid = FALSE;

	/**
	 * Iterator interface
	 *
	 * @see Iterator::rewind()
	 *
	 * @return void
	 */
	public function rewind() {
		$this->valid = (FALSE !== reset($this->attributes));
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::current()
	 *
	 * @return void
	 */
	public function current() {
		return current($this->attributes);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::key()
	 *
	 * @return void
	 */
	public function key() {
		return key($this->attributes);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::next()
	 *
	 * @return void
	 */
	public function next() {
		$this->valid = (FALSE !== next($this->attributes));
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::valid()
	 *
	 * @return void
	 */
	public function valid() {
		return $this->valid;
	}

	/*
	 * ARRAY ACCESS INTERFACE
	 */

	/*
	 * This lets an entity's attributes be accessed like an associative array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	/**
	 * Array access interface
	 *
	 * @see ArrayAccess::offsetSet()
	 *
	 * @param mixed $key   Name
	 * @param mixed $value Value
	 *
	 * @return void
	 */
	public function offsetSet($key, $value) {
		if (array_key_exists($key, $this->attributes)) {
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * Array access interface
	 *
	 * @see ArrayAccess::offsetGet()
	 *
	 * @param mixed $key Name
	 *
	 * @return void
	 */
	public function offsetGet($key) {
		if (array_key_exists($key, $this->attributes)) {
			return $this->attributes[$key];
		}
	}

	/**
	 * Array access interface
	 *
	 * @see ArrayAccess::offsetUnset()
	 *
	 * @param mixed $key Name
	 *
	 * @return void
	 */
	public function offsetUnset($key) {
		if (array_key_exists($key, $this->attributes)) {
			// Full unsetting is dangerous for our objects
			$this->attributes[$key] = "";
		}
	}

	/**
	 * Array access interface
	 *
	 * @see ArrayAccess::offsetExists()
	 *
	 * @param int $offset Offset
	 *
	 * @return int
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->attributes);
	}
}