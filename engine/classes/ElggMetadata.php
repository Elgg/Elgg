<?php

/**
 * ElggMetadata
 *
 * This class describes metadata that can be attached to an ElggEntity. It is
 * rare that a plugin developer needs to use this API for metadata. Almost all
 * interaction with metadata occurs through the methods of ElggEntity. See its
 * __set(), __get(), and setMetadata() methods.
 *
 * @package    Elgg.Core
 * @subpackage Metadata
 */
class ElggMetadata extends ElggExtender {

	/**
	 * (non-PHPdoc)
	 *
	 * @see ElggData::initializeAttributes()
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "metadata";
	}

	/**
	 * Construct a metadata object
	 *
	 * Plugin developers will probably never need to use this API. See ElggEntity
	 * for its API for setting and getting metadata.
	 *
	 * @param stdClass $row Database row as stdClass object
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		if (!empty($row)) {
			// Create from db row
			if ($row instanceof stdClass) {
				$metadata = $row;

				$objarray = (array) $metadata;
				foreach ($objarray as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else {
				// get an ElggMetadata object and copy its attributes
				elgg_deprecated_notice('Passing an ID to constructor is deprecated. Use elgg_get_metadata_from_id()', 1.9);
				$metadata = elgg_get_metadata_from_id($row);
				$this->attributes = $metadata->attributes;
			}
		}
	}

	/**
	 * Determines whether or not the user can edit this piece of metadata
	 *
	 * @param int $user_guid The GUID of the user (defaults to currently logged in user)
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canEdit($user_guid = 0) {
		if ($entity = get_entity($this->entity_guid)) {
			return $entity->canEditMetadata($this, $user_guid);
		}
		return false;
	}

	/**
	 * Save metadata object
	 *
	 * @return int|bool the metadata object id or true if updated
	 *
	 * @throws IOException
	 */
	public function save() {
		if ($this->id > 0) {
			return update_metadata($this->id, $this->name, $this->value,
				$this->value_type, $this->owner_guid, $this->access_id);
		} else {
			$this->id = create_metadata($this->entity_guid, $this->name, $this->value,
				$this->value_type, $this->owner_guid, $this->access_id);

			if (!$this->id) {
				throw new IOException("Unable to save new " . get_class());
			}
			return $this->id;
		}
	}

	/**
	 * Delete the metadata
	 *
	 * @return bool
	 */
	public function delete() {
		$success = _elgg_delete_metastring_based_object_by_id($this->id, 'metadata');
		if ($success) {
			// we mark unknown here because this deletes only one value
			// under this name, and there may be others remaining.
			_elgg_get_metadata_cache()->markUnknown($this->entity_guid, $this->name);
		}
		return $success;
	}

	/**
	 * Disable the metadata
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function disable() {
		$success = _elgg_set_metastring_based_object_enabled_by_id($this->id, 'no', 'metadata');
		if ($success) {
			// we mark unknown here because this disables only one value
			// under this name, and there may be others remaining.
			_elgg_get_metadata_cache()->markUnknown($this->entity_guid, $this->name);
		}
		return $success;
	}

	/**
	 * Enable the metadata
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function enable() {
		$success = _elgg_set_metastring_based_object_enabled_by_id($this->id, 'yes', 'metadata');
		if ($success) {
			_elgg_get_metadata_cache()->markUnknown($this->entity_guid, $this->name);
		}
		return $success;
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
