<?php

/**
 * Entity Annotation
 *
 * Annotations allow you to attach bits of information to entities.
 * Unlike entity metadata, annotation is access controlled and has owners.
 */
class ElggAnnotation extends \ElggExtender {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = 'annotation';
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
	}

	/**
	 * Save this instance and returns an annotation ID
	 *
	 * @return bool
	 */
	public function save(): bool {
		if (!isset($this->access_id)) {
			$this->access_id = ACCESS_PRIVATE;
		}

		if (!isset($this->owner_guid)) {
			$this->owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}

		if ($this->id) {
			return _elgg_services()->annotationsTable->update($this);
		}

		$entity = get_entity($this->entity_guid);
		if (!$entity) {
			return false;
		}

		if (_elgg_services()->annotationsTable->create($this, $entity)) {
			return true;
		}

		return false;
	}

	/**
	 * Delete the annotation.
	 *
	 * @return bool
	 */
	public function delete() {
		return _elgg_services()->annotationsTable->delete($this);
	}

	/**
	 * Disable the annotation.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function disable() {
		return _elgg_services()->annotationsTable->disable($this);
	}

	/**
	 * Enable the annotation.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function enable() {
		return _elgg_services()->annotationsTable->enable($this);
	}

	/**
	 * Determines whether or not the user can edit this annotation
	 *
	 * @param int $user_guid The GUID of the user (defaults to currently logged in user)
	 *
	 * @return bool
	 */
	public function canEdit($user_guid = 0) {
		$entity = $this->getEntity();

		return _elgg_services()->userCapabilities->canEditAnnotation($entity, $user_guid, $this);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObjectFromID($id) {
		return elgg_get_annotation_from_id($id);
	}
}
