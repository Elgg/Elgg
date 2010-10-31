<?php
abstract class ElggData implements
	Loggable,	// Can events related to this object class be logged
	Iterator,	// Override foreach behaviour
	ArrayAccess, // Override for array access
	Exportable
{
	
	/**
	 * The main attributes of an entity.
	 * Holds attributes to save to database
	 * This contains the site's main properties (id, etc)
	 * Blank entries for all database fields should be created by the constructor.
	 * Subclasses should add to this in their constructors.
	 * Any field not appearing in this will be viewed as a
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
		
		$this->attributes['time_created'] = '';
	}
	
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
	 * Return the guid of the entity's owner.
	 *
	 * @return int The owner GUID
	 */
	public function getOwner() {
		return $this->owner_guid;
	}

	/**
	 * Returns the ElggEntity or child object of the owner of the entity.
	 *
	 * @return ElggEntity The owning user
	 */
	public function getOwnerEntity() {
		return get_entity($this->owner_guid);
	}
	
	/**
	 * Returns the UNIX epoch time that this entity was created
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeCreated() {
		return $this->time_created;
	}
	
	/*
	 *  SYSTEM LOG INTERFACE
	 */

	/**
	 * Return the class name of the object.
	 *
	 * @return string
	 */
	public function getClassName() {
		return get_class($this);
	}

	/**
	 * Return the GUID of the owner of this object.
	 *
	 * @return int
	 */
	public function getObjectOwnerGUID() {
		return $this->owner_guid;
	}

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