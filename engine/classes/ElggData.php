<?php

use Elgg\Collections\CollectionItemInterface;
use Elgg\Traits\TimeUsing;

/**
 * A generic class that contains shared code among
 * \ElggExtender, \ElggEntity, and \ElggRelationship
 */
abstract class ElggData implements CollectionItemInterface,
								   Iterator,
								   ArrayAccess {

	use TimeUsing;

	/**
	 * The main attributes of an entity.
	 * Holds attributes to save to database
	 * Blank entries for all database fields should be created by the constructor.
	 * Subclasses should add to this in their constructors.
	 * Any field not appearing in this will be viewed as metadata
	 *
	 * @var array
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
		$this->attributes['time_created'] = null;
	}

	/**
	 * Provides a pointer to the database object.
	 *
	 * @return \Elgg\Database The database where this data is (will be) stored.
	 */
	protected function getDatabase(): \Elgg\Database {
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
	 * Unset a property from metadata or attribute.
	 *
	 * @warning If you use this to unset an attribute, you must save the object!
	 *
	 * @param string $name The name of the attribute or metadata.
	 *
	 * @return void
	 */
	public function __unset($name) {
		$this->$name = null;
	}

	/**
	 * Get a URL for this object
	 *
	 * @return string
	 */
	abstract public function getURL(): string;

	/**
	 * Save this data to the appropriate database table.
	 *
	 * @return bool
	 */
	abstract public function save(): bool;

	/**
	 * Delete this data.
	 *
	 * @return bool
	 */
	abstract public function delete(): bool;

	/**
	 * Returns the UNIX epoch time that this entity was created
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeCreated(): int {
		return (int) $this->attributes['time_created'];
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
	 * SYSTEM LOG RELATED FUNCTIONS
	 */
	
	/**
	 * Return an identification for the object for storage in the system log.
	 * This id must be an integer. Unsaved implementations should return 0.
	 *
	 * @return int
	 */
	abstract public function getSystemLogID(): int;
	
	/**
	 * Return the type of the object - eg. object, group, user, relationship, metadata, annotation etc
	 *
	 * @return string
	 */
	abstract public function getType(): string;
	
	/**
	 * Return a subtype. For metadata & annotations this is the 'name' and for relationship this is the
	 * relationship type.
	 *
	 * @return string
	 */
	abstract public function getSubtype(): string;
	
	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id GUID of an entity
	 *
	 * @return static|false
	 */
	abstract public function getObjectFromID(int $id);

	/**
	 * ITERATOR INTERFACE
	 */
	
	/**
	 * @var bool is the iterator still valid
	 */
	protected $valid = false;

	/**
	 * Iterator interface
	 *
	 * @see Iterator::rewind()
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function rewind() {
		$this->valid = (reset($this->attributes) !== false);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::current()
	 *
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
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
	#[\ReturnTypeWillChange]
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
	#[\ReturnTypeWillChange]
	public function next() {
		$this->valid = (next($this->attributes) !== false);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::valid()
	 *
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
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
	 * @param mixed $offset The offset to assign the value to
	 * @param mixed $value  The value to set
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet($offset, $value) {
		if (array_key_exists($offset, $this->attributes)) {
			$this->attributes[$offset] = $value;
		}
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetGet()
	 *
	 * @param mixed $offset The offset to retrieve
	 *
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset) {
		if (array_key_exists($offset, $this->attributes)) {
			return $this->$offset;
		}

		return null;
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetUnset()
	 *
	 * @param mixed $offset The offset to unset
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset($offset) {
		if (array_key_exists($offset, $this->attributes)) {
			// Full unsetting is dangerous for our objects
			unset($this->$offset);
		}
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetExists()
	 *
	 * @param int $offset An offset to check for
	 *
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getID() {
		return $this->getSystemLogID();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority() {
		return $this->getTimeCreated();
	}

	/**
	 * Called during serialization
	 *
	 * @return array
	 * @see serialize()
	 * @since 4.1
	 */
	public function __serialize(): array {
		return $this->attributes;
	}

	/**
	 * Called during unserialization
	 *
	 * @param array $data serialized data
	 *
	 * @return void
	 * @see unserialize()
	 * @since 4.1
	 */
	public function __unserialize(array $data): void {
		$this->attributes = $data;
	}
}
