<?php

/**
 * ElggMetadata
 * This class describes metadata that can be attached to ElggEntities.
 *
 * @package    Elgg.Core
 * @subpackage Metadata
 */
class ElggMetadata extends ElggExtender {

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "metadata";
	}

	/**
	 * Construct a metadata object
	 *
	 * @param mixed $id ID of metadata or a database row as stdClass object
	 *
	 * @return void
	 */
	function __construct($id = null) {
		$this->initializeAttributes();

		if (!empty($id)) {
			// Create from db row
			if ($id instanceof stdClass) {
				$metadata = $id;
				
				$objarray = (array) $metadata;
				foreach ($objarray as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else {
				// get an ElggMetadata object and copy its attributes
				$metadata = elgg_get_metadata_from_id($id);
				$this->attributes = $metadata->attributes;
			}
		}
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
			return update_metadata($this->id, $this->name, $this->value,
				$this->value_type, $this->owner_guid, $this->access_id);
		} else {
			$this->id = create_metadata($this->entity_guid, $this->name, $this->value,
				$this->value_type, $this->owner_guid, $this->access_id);

			if (!$this->id) {
				throw new IOException(elgg_echo('IOException:UnableToSaveNew', array(get_class())));
			}
			return $this->id;
		}
	}

	/**
	 * Delete the metadata
	 *
	 * @return bool
	 */
	function delete() {
		return elgg_delete_metastring_based_object_by_id($this->id, 'metadata');
	}

	/**
	 * Disable the metadata
	 *
	 * @return bool
	 * @since 1.8
	 */
	function disable() {
		return elgg_set_metastring_based_object_enabled_by_id($this->id, 'no', 'metadata');
	}

	/**
	 * Disable the metadata
	 *
	 * @return bool
	 * @since 1.8
	 */
	function enable() {
		return elgg_set_metastring_based_object_enabled_by_id($this->id, 'yes', 'metadata');
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
	 *
	 * @param int $id Metadata ID
	 *
	 * @return ElggMetadata
	 */
	public function getObjectFromID($id) {
		return elgg_get_metadata_from_id($id);
	}
}
