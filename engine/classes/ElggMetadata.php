<?php

/**
 * ElggMetadata
 * This class describes metadata that can be attached to ElggEntities.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
class ElggMetadata extends ElggExtender {
	/**
	 * Construct a new site object, optionally from a given id value or row.
	 *
	 * @param mixed $id
	 */
	function __construct($id = null) {
		$this->attributes = array();

		if (!empty($id)) {
			// Create from db row
			if ($id instanceof stdClass) {
				$metadata = $id;
			} else {
				$metadata = get_metadata($id);
			}

			if ($metadata) {
				$objarray = (array) $metadata;
				foreach($objarray as $key => $value) {
					$this->attributes[$key] = $value;
				}
				$this->attributes['type'] = "metadata";
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
		return $this->get($name);
	}

	/**
	 * Class member set overloading
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function __set($name, $value) {
		return $this->set($name, $value);
	}

	/**
	 * Determines whether or not the user can edit this piece of metadata
	 *
	 * @return true|false Depending on permissions
	 */
	function canEdit() {
		if ($entity = get_entity($this->get('entity_guid'))) {
			return $entity->canEditMetadata($this);
		}
		return false;
	}

	/**
	 * Save matadata object
	 *
	 * @return int the metadata object id
	 */
	function save() {
		if ($this->id > 0) {
			return update_metadata($this->id, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
		} else {
			$this->id = create_metadata($this->entity_guid, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
			if (!$this->id) {
				throw new IOException(sprintf(elgg_echo('IOException:UnableToSaveNew'), get_class()));
			}
			return $this->id;
		}
	}

	/**
	 * Delete a given metadata.
	 */
	function delete() {
		return delete_metadata($this->id);
	}

	/**
	 * Get a url for this item of metadata.
	 *
	 * @return string
	 */
	public function getURL() {
		return get_metadata_url($this->id);
	}

	// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 */
	public function getObjectFromID($id) {
		return get_metadata($id);
	}
}