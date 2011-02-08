<?php
/**
 * A generic class that contains shared code b/w
 * ElggExtender, ElggEntity, and ElggRelationship
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 */
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
	 * Initialise the attributes array.
	 *
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * @param bool $pre18_api Compatibility for subclassing in 1.7 -> 1.8 change.
	 *                        Passing true (default) emits a deprecation notice.
	 *                        Passing false returns false.  Core constructors always pass false.
	 *                        Does nothing either way since attributes are initialized by the time
	 *                        this is called.
	 * @return false|void False is
	 * @deprecated 1.8 Use initializeAttributes()
	 */
	protected function initialise_attributes($pre18_api = true) {
		if ($pre18_api) {
			elgg_deprecated_notice('initialise_attributes() is deprecated by initializeAttributes()', 1.8);
		} else {
			return false;
		}
	}

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

		$this->attributes['time_created'] = NULL;
	}

	/**
	 * Return an attribute or a piece of metadata.
	 *
	 * @param string $name Name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return $this->get($name);
	}

	/**
	 * Set an attribute or a piece of metadata.
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return mixed
	 */
	public function __set($name, $value) {
		return $this->set($name, $value);
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
	function __isset($name) {
		return $this->$name !== NULL;
	}

	/**
	 * Fetch the specified attribute
	 *
	 * @param string $name The attribute to fetch
	 *
	 * @return mixed The attribute, if it exists.  Otherwise, null.
	 */
	abstract protected function get($name);

	/**
	 * Set the specified attribute
	 *
	 * @param string $name  The attribute to set
	 * @param mixed  $value The value to set it to
	 *
	 * @return The success of your set funtion?
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
	 * @deprecated 1.8 Use getOwnerGUID() instead
	 */
	public function getObjectOwnerGUID() {
		elgg_deprecated_notice("getObjectOwnerGUID() was deprecated.  Use getOwnerGUID().", 1.8);
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
