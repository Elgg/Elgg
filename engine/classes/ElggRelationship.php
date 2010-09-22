<?php
/**
 * Relationship class.
 *
 * @package Elgg.Core
 * @subpackage Core
 */
class ElggRelationship implements
	Importable,
	Exportable,
	Loggable,	// Can events related to this object class be logged
	Iterator,	// Override foreach behaviour
	ArrayAccess // Override for array access
	{
	/**
	 * This contains the site's main properties (id, etc)
	 * @var array
	 */
	protected $attributes;

	/**
	 * Construct a new site object, optionally from a given id value or row.
	 *
	 * @param mixed $id
	 */
	function __construct($id = null) {
		$this->attributes = array();

		if (!empty($id)) {
			if ($id instanceof stdClass) {
				$relationship = $id; // Create from db row
			} else {
				$relationship = get_relationship($id);
			}

			if ($relationship) {
				$objarray = (array) $relationship;
				foreach($objarray as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}
		}
	}

	/**
	 * Class member get overloading
	 *
	 * @param string $name
	 * @return mixed
	 */
	function __get($name) {
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}

		return null;
	}

	/**
	 * Class member set overloading
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function __set($name, $value) {
		$this->attributes[$name] = $value;
		return true;
	}

	/**
	 * Save the relationship
	 *
	 * @return int the relationship id
	 */
	public function save() {
		if ($this->id > 0) {
			delete_relationship($this->id);
		}

		$this->id = add_entity_relationship($this->guid_one, $this->relationship, $this->guid_two);
		if (!$this->id) {
			throw new IOException(sprintf(elgg_echo('IOException:UnableToSaveNew'), get_class()));
		}

		return $this->id;
	}

	/**
	 * Delete a given relationship.
	 */
	public function delete() {
		return delete_relationship($this->id);
	}

	/**
	 * Get a URL for this relationship.
	 *
	 * @return string
	 */
	public function getURL() {
		return get_relationship_url($this->id);
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array(
			'id',
			'guid_one',
			'relationship',
			'guid_two'
		);
	}

	/**
	 * Export this relationship
	 *
	 * @return array
	 */
	public function export() {
		$uuid = get_uuid_from_object($this);
		$relationship = new ODDRelationship(
			guid_to_uuid($this->guid_one),
			$this->relationship,
			guid_to_uuid($this->guid_two)
		);

		$relationship->setAttribute('uuid', $uuid);

		return $relationship;
	}

	// IMPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Import a relationship
	 *
	 * @param array $data
	 * @param int $version
	 * @return ElggRelationship
	 * @throws ImportException
	 */
	public function import(ODD $data) {
		if (!($element instanceof ODDRelationship)) {
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnexpectedODDClass'));
		}

		$uuid_one = $data->getAttribute('uuid1');
		$uuid_two = $data->getAttribute('uuid2');

		// See if this entity has already been imported, if so then we need to link to it
		$entity1 = get_entity_from_uuid($uuid_one);
		$entity2 = get_entity_from_uuid($uuid_two);
		if (($entity1) && ($entity2)) {
			// Set the item ID
			$this->attributes['guid_one'] = $entity1->getGUID();
			$this->attributes['guid_two'] = $entity2->getGUID();

			// Map verb to relationship
			//$verb = $data->getAttribute('verb');
			//$relationship = get_relationship_from_verb($verb);
			$relationship = $data->getAttribute('type');

			if ($relationship) {
				$this->attributes['relationship'] = $relationship;
				// save
				$result = $this->save();
				if (!$result) {
					throw new ImportException(sprintf(elgg_echo('ImportException:ProblemSaving'), get_class()));
				}

				return $this;
			}
		}
	}

	// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an identification for the object for storage in the system log.
	 * This id must be an integer.
	 *
	 * @return int
	 */
	public function getSystemLogID() {
		return $this->id;
	}

	/**
	 * Return the class name of the object.
	 */
	public function getClassName() {
		return get_class($this);
	}

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 */
	public function getObjectFromID($id) {
		return get_relationship($id);
	}

	/**
	 * Return the GUID of the owner of this object.
	 */
	public function getObjectOwnerGUID() {
		return $this->owner_guid;
	}

	/**
	 * Return a type of the object - eg. object, group, user, relationship, metadata, annotation etc
	 */
	public function getType() {
		return 'relationship';
	}

	/**
	 * Return a subtype. For metadata & annotations this is the 'name' and for relationship this is the relationship type.
	 */
	public function getSubtype() {
		return $this->relationship;
	}

	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be displayed using foreach as a normal array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	private $valid = FALSE;

	function rewind() {
		$this->valid = (FALSE !== reset($this->attributes));
	}

	function current() {
		return current($this->attributes);
	}

	function key() {
		return key($this->attributes);
	}

	function next() {
		$this->valid = (FALSE !== next($this->attributes));
	}

	function valid() {
		return $this->valid;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be accessed like an associative array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	function offsetSet($key, $value) {
		if ( array_key_exists($key, $this->attributes) ) {
			$this->attributes[$key] = $value;
		}
	}

	function offsetGet($key) {
		if ( array_key_exists($key, $this->attributes) ) {
			return $this->attributes[$key];
		}
	}

	function offsetUnset($key) {
		if ( array_key_exists($key, $this->attributes) ) {
			$this->attributes[$key] = ""; // Full unsetting is dangerious for our objects
		}
	}

	function offsetExists($offset) {
		return array_key_exists($offset, $this->attributes);
	}
}