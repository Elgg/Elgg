<?php

/**
 * Relationship class.
 *
 * @property int    $id           The unique identifier (read-only)
 * @property int    $guid_one     The GUID of the subject of the relationship
 * @property string $relationship The type of the relationship (limit of 50 characters long)
 * @property int    $guid_two     The GUID of the target of the relationship
 * @property int    $time_created A UNIX timestamp of when the relationship was created (read-only, set on first save)
 */
class ElggRelationship extends \ElggData {
	// database column limit
	const RELATIONSHIP_LIMIT = 50;

	/**
	 * @var string[] attributes that are integers
	 */
	protected const INTEGER_ATTR_NAMES = [
		'guid_one',
		'guid_two',
		'time_created',
		'id',
	];
	
	/**
	 * Create a relationship object
	 *
	 * @param \stdClass $row Database row
	 */
	public function __construct(\stdClass $row) {
		$this->initializeAttributes();

		foreach ((array) $row as $key => $value) {
			if (in_array($key, static::INTEGER_ATTR_NAMES)) {
				$value = (int) $value;
			}
			
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \ElggData::initializeAttributes()
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['id'] = null;
		$this->attributes['guid_one'] = null;
		$this->attributes['relationship'] = null;
		$this->attributes['guid_two'] = null;
	}

	/**
	 * Set an attribute of the relationship
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->attributes[$name] = $value;
	}

	/**
	 * Get an attribute of the relationship
	 *
	 * @param string $name Name
	 * @return mixed
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function save() : bool {
		if ($this->id > 0) {
			delete_relationship($this->id);
		}

		$id = _elgg_services()->relationshipsTable->add(
			$this->guid_one,
			$this->relationship,
			$this->guid_two,
			true
		);
		
		if ($id === false) {
			return false;
		}
		
		$this->id = $id;

		return true;
	}

	/**
	 * Delete this relationship from the database.
	 *
	 * @return bool
	 */
	public function delete() {
		return delete_relationship($this->id);
	}

	/**
	 * Get a URL for this relationship.
	 *
	 * Plugins can register for the 'relationship:url', 'relationship' plugin hook to
	 * customize the url for a relationship.
	 *
	 * @return string
	 */
	public function getURL() {
		$url = _elgg_services()->hooks->trigger('relationship:url', $this->getType(), ['relationship' => $this], '');

		return elgg_normalize_url($url);
	}

	/**
	 * {@inheritdoc}
	 */
	public function toObject(array $params = []) {
		$object = new \Elgg\Export\Relationship();
		$object->id = $this->id;
		$object->subject_guid = $this->guid_one;
		$object->relationship = $this->relationship;
		$object->object_guid = $this->guid_two;
		$object->time_created = date('c', $this->getTimeCreated());

		$params['relationship'] = $this;

		return _elgg_services()->hooks->trigger('to:object', 'relationship', $params, $object);
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
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id ID
	 *
	 * @return \ElggRelationship|false
	 */
	public function getObjectFromID($id) {
		return get_relationship($id);
	}

	/**
	 * Return a type of the object - eg. object, group, user, relationship, metadata, annotation etc
	 *
	 * @return string 'relationship'
	 */
	public function getType() {
		return 'relationship';
	}

	/**
	 * Return a subtype. For metadata & annotations this is the 'name' and for relationship this
	 * is the relationship type.
	 *
	 * @return string
	 */
	public function getSubtype() {
		return $this->relationship;
	}
}
