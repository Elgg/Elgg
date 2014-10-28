<?php
/**
 * Relationship class.
 *
 * @package    Elgg.Core
 * @subpackage Core
 *
 * @property int    $id           The unique identifier (read-only)
 * @property int    $guid_one     The GUID of the subject of the relationship
 * @property string $relationship The type of the relationship (limit of 50 characters long)
 * @property int    $guid_two     The GUID of the target of the relationship
 * @property int    $time_created A UNIX timestamp of when the relationship was created (read-only, set on first save)
 */
class ElggRelationship extends ElggData implements
	Importable // deprecated
{
	// database column limit
	const RELATIONSHIP_LIMIT = 50;

	/**
	 * Create a relationship object
	 *
	 * @param stdClass $row Database row or null for new relationship
	 * @throws InvalidArgumentException
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		if ($row === null) {
			elgg_deprecated_notice('Passing null to constructor is deprecated. Use add_entity_relationship()', 1.9);
			return;
		}

		if (!($row instanceof stdClass)) {
			if (!is_numeric($row)) {
				throw new InvalidArgumentException("Constructor accepts only a stdClass or null.");
			}

			$id = (int)$row;
			elgg_deprecated_notice('Passing an ID to constructor is deprecated. Use get_relationship()', 1.9);
			$row = _elgg_get_relationship_row($id);
			if (!$row) {
				throw new InvalidArgumentException("Relationship not found with ID $id");
			}
		}

		foreach ((array)$row as $key => $value) {
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see ElggData::initializeAttributes()
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
	 * Class member set overloading
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 * @return mixed
	 * @deprecated 1.9
	 */
	public function set($name, $value) {
		elgg_deprecated_notice("Use -> instead of set()", 1.9);
		$this->__set($name, $value);
		return true;
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
	 * Class member get overloading
	 *
	 * @param string $name Name
	 * @return mixed
	 * @deprecated 1.9
	 */
	public function get($name) {
		elgg_deprecated_notice("Use -> instead of get()", 1.9);
		return $this->__get($name);
	}

	/**
	 * Save the relationship
	 *
	 * @return int the relationship ID
	 * @throws IOException
	 */
	public function save() {
		if ($this->id > 0) {
			delete_relationship($this->id);
		}

		$this->id = add_entity_relationship($this->guid_one, $this->relationship, $this->guid_two);
		if (!$this->id) {
			throw new IOException("Unable to save new " . get_class());
		}

		return $this->id;
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
		$url = '';
		// @todo remove when elgg_register_relationship_url_handler() has been removed
		if ($this->id) {
			global $CONFIG;

			$subtype = $this->getSubtype();

			$function = "";
			if (isset($CONFIG->relationship_url_handler[$subtype])) {
				$function = $CONFIG->relationship_url_handler[$subtype];
			}
			if (isset($CONFIG->relationship_url_handler['all'])) {
				$function = $CONFIG->relationship_url_handler['all'];
			}

			if (is_callable($function)) {
				$url = call_user_func($function, $this);
			}

			if ($url) {
				$url = elgg_normalize_url($url);
			}
		}

		$type = $this->getType();
		$params = array('relationship' => $this);
		$url = elgg_trigger_plugin_hook('relationship:url', $type, $params, $url);

		return elgg_normalize_url($url);
	}

	/**
	 * {@inheritdoc}
	 */
	public function toObject() {
		$object = new stdClass();
		$object->id = $this->id;
		$object->subject_guid = $this->guid_one;
		$object->relationship = $this->relationship;
		$object->object_guid = $this->guid_two;
		$object->time_created = date('c', $this->getTimeCreated());
		$params = array('relationship' => $this);
		return elgg_trigger_plugin_hook('to:object', 'relationship', $params, $object);
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 * @deprecated 1.9 Use toObject()
	 */
	public function getExportableValues() {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated by toObject()', 1.9);
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
	 * @deprecated 1.9 Use toObject()
	 */
	public function export() {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);
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
	 * @param ODD $data ODD data

	 * @return bool
	 * @throws ImportException|InvalidParameterException
	 * @deprecated 1.9
	 */
	public function import(ODD $data) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);
		if (!($data instanceof ODDRelationship)) {
			throw new InvalidParameterException("import() passed an unexpected ODD class");
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
					throw new ImportException("There was a problem saving " . get_class());
				}

				return true;
			}
		}

		return false;
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
	 * @return ElggRelationship
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
