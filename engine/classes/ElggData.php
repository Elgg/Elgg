<?php

use Elgg\Collections\CollectionItemInterface;

/**
 * A generic class that contains shared code among
 * \ElggExtender, \ElggEntity, and \ElggRelationship
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 */
abstract class ElggData implements CollectionItemInterface,
								   Serializable,
								   Loggable,
								   Iterator,
								   ArrayAccess {

	use \Elgg\TimeUsing;

	/**
	 * The main attributes of an entity.
	 * Holds attributes to save to database
	 * Blank entries for all database fields should be created by the constructor.
	 * Subclasses should add to this in their constructors.
	 * Any field not appearing in this will be viewed as metadata
	 */
	protected $attributes = [];

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
			$this->attributes = [];
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
		return $this->attributes['time_created'];
	}

	/**
	 * Get a plain old object copy for public consumption
	 *
	 * @param array $params Export parameters
	 *
	 * @return \Elgg\Export\Data
	 */
	abstract public function toObject(array $params = []);

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

	/**
	 * {@inheritdoc}
	 */
	public function getId() {
		return $this->getSystemLogID();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority() {
		return $this->getTimeCreated();
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize() {
		return serialize($this->attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized) {
		$this->attributes = unserialize($serialized);
	}
}
