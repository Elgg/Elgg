<?php
/**
 * A generic class that contains shared code among
 * \ElggExtender, \ElggEntity, and \ElggRelationship
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 */
abstract class ElggData implements
	Loggable,    // Can events related to this object class be logged
	Iterator,    // Override foreach behaviour
	\ArrayAccess, // Override for array access
	Exportable   // (deprecated 1.9)
{

	use \Elgg\TimeUsing;
	
	/**
	 * The main attributes of an entity.
	 * Holds attributes to save to database
	 * Blank entries for all database fields should be created by the constructor.
	 * Subclasses should add to this in their constructors.
	 * Any field not appearing in this will be viewed as metadata
	 */
	protected $attributes = array();

	/**
	 * Initialize the attributes array.
	 *
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		// Create attributes array if not already created
		if (!is_array($this->attributes)) {
			$this->attributes = array();
		}

		$this->attributes['time_created'] = null;
	}

	/**
	 * Provides a pointer to the database object.
	 *
	 * @return \Elgg\Database The database where this data is (will be) stored.
	 */
	protected function getDatabase() {
		return _elgg_services()->db;
	}

	/**
	 * Test if property is set either as an attribute or metadata.
	 *
	 * @tip Use isset($entity->property)
	 *
	 * @param string $name The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	public function __isset($name) {
		return $this->$name !== null;
	}

	/**
	 * Fetch the specified attribute
	 *
	 * @param string $name The attribute to fetch
	 *
	 * @return mixed The attribute, if it exists.  Otherwise, null.
	 * @deprecated 1.9
	 */
	abstract protected function get($name);

	/**
	 * Set the specified attribute
	 *
	 * @param string $name  The attribute to set
	 * @param mixed  $value The value to set it to
	 *
	 * @return bool The success of your set function?
	 * @deprecated 1.9
	 */
	abstract protected function set($name, $value);

	/**
	 * Get a URL for this object
	 *
	 * @return string
	 */
	abstract public function getURL();

	/**
	 * Save this data to the appropriate database table.
	 *
	 * @return bool
	 */
	abstract public function save();
	
	/**
	 * Delete this data.
	 *
	 * @return bool
	 */
	abstract public function delete();

	/**
	 * Returns the UNIX epoch time that this entity was created
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeCreated() {
		return $this->time_created;
	}

	/**
	 * Get a plain old object copy for public consumption
	 * 
	 * @return \stdClass
	 */
	abstract public function toObject();

	/*
	 *  SYSTEM LOG INTERFACE
	 */

	/**
	 * Return the class name of the object.
	 *
	 * @return string
	 * @deprecated 1.9 Use get_class()
	 */
	public function getClassName() {
		elgg_deprecated_notice("getClassName() is deprecated. Use get_class().", 1.9);
		return get_class($this);
	}

	/*
	 * ITERATOR INTERFACE
	 */

	protected $valid = false;

	/**
	 * Iterator interface
	 *
	 * @see Iterator::rewind()
	 *
	 * @return void
	 */
	public function rewind() {
		$this->valid = (false !== reset($this->attributes));
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::current()
	 *
	 * @return mixed
	 */
	public function current() {
		return current($this->attributes);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::key()
	 *
	 * @return string
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
		$this->valid = (false !== next($this->attributes));
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::valid()
	 *
	 * @return bool
	 */
	public function valid() {
		return $this->valid;
	}

	/*
	 * ARRAY ACCESS INTERFACE
	 */

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetSet()
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
	 * @see \ArrayAccess::offsetGet()
	 *
	 * @param mixed $key Name
	 *
	 * @return mixed
	 */
	public function offsetGet($key) {
		if (array_key_exists($key, $this->attributes)) {
			return $this->attributes[$key];
		}
		return null;
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetUnset()
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
	 * @see \ArrayAccess::offsetExists()
	 *
	 * @param int $offset Offset
	 *
	 * @return int
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->attributes);
	}
}
