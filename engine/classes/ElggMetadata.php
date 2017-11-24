<?php

/**
 * \ElggMetadata
 *
 * This class describes metadata that can be attached to an \ElggEntity. It is
 * rare that a plugin developer needs to use this API for metadata. Almost all
 * interaction with metadata occurs through the methods of \ElggEntity. See its
 * __set(), __get(), and setMetadata() methods.
 *
 * @package    Elgg.Core
 * @subpackage Metadata
 *
 * @property int $access_id Access level of the metadata (deprecated). Only set this to ACCESS_PUBLIC
 *                          for compatibility with Elgg 3.0
 */
class ElggMetadata extends \ElggExtender {

	/**
	 * (non-PHPdoc)
	 *
	 * @see \ElggData::initializeAttributes()
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
	 * Plugin developers will probably never need to use this API. See \ElggEntity
	 * for its API for setting and getting metadata.
	 *
	 * @param \stdClass $row Database row as \stdClass object
	 */
	public function __construct(\stdClass $row = null) {
		$this->initializeAttributes();

		if ($row) {
			foreach ((array) $row as $key => $value) {
				$this->attributes[$key] = $value;
			}
		}

		$this->attributes['access_id'] = ACCESS_PUBLIC;
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
			return _elgg_services()->metadataTable->update($this->id, $this->name, $this->value, $this->value_type);
		}

		$this->id = _elgg_services()->metadataTable->create($this->entity_guid, $this->name, $this->value, $this->value_type);

		if (!$this->id) {
			throw new \IOException("Unable to save new " . get_class());
		}

		return $this->id;
	}

	/**
	 * Delete the metadata
	 *
	 * @return bool
	 */
	public function delete() {
		if (!$this->canEdit()) {
			return false;
		}

		if (!elgg_trigger_event('delete', $this->getType(), $this)) {
			return false;
		}

		$qb = \Elgg\Database\Delete::fromTable('metadata');
		$qb->where($qb->compare('id', '=', $this->id, ELGG_VALUE_INTEGER));

		$deleted = _elgg_services()->db->deleteData($qb);

		if ($deleted) {
			_elgg_services()->metadataCache->clear($this->entity_guid);
		}

		return $deleted;
	}

	// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id Metadata ID
	 *
	 * @return \ElggMetadata
	 */
	public function getObjectFromID($id) {
		return elgg_get_metadata_from_id($id);
	}
}
