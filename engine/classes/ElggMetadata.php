<?php

/**
 * ElggMetadata
 *
 * This class describes metadata that can be attached to an \ElggEntity.
 * It is rare that a plugin developer needs to use this API for metadata.
 * Almost all interaction with metadata occurs through the methods of \ElggEntity.
 * See its __set(), __get(), and setMetadata() methods.
 */
class ElggMetadata extends ElggExtender {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "metadata";
	}

	/**
	 * Constructor
	 *
	 * @param stdClass $row Database row
	 */
	public function __construct(stdClass $row = null) {
		$this->initializeAttributes();

		if ($row) {
			foreach ((array) $row as $key => $value) {
				$this->$key = $value;
			}
		}

		$this->access_id = ACCESS_PUBLIC;
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
	 * Returns metadata on success, false on failure
	 *
	 * @return int|false
	 */
	public function save() {
		if (!$this->id) {
			return _elgg_services()->metadataTable->create($this);
		}

		return _elgg_services()->metadataTable->update($this);
	}

	/**
	 * Delete the metadata
	 *
	 * @return bool
	 */
	public function delete() {
		return _elgg_services()->metadataTable->delete($this);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObjectFromID($id) {
		return elgg_get_metadata_from_id($id);
	}
}
