<?php

use Elgg\Database\EntityTable\UserFetchFailureException;

/**
 * Elgg Annotations
 *
 * Annotations allow you to attach bits of information to entities. They are
 * essentially the same as metadata, but with additional helper functions for
 * performing calculations.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Annotations
 */
class ElggAnnotation extends \ElggExtender {

	/**
	 * (non-PHPdoc)
	 *
	 * @see \ElggData::initializeAttributes()
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = 'annotation';
	}

	/**
	 * Construct a new annotation object
	 *
	 * Plugin developers will probably never use the constructor.
	 * See \ElggEntity for its API for adding annotations.
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
	}

	/**
	 * Save this instance
	 *
	 * @return int an object id
	 *
	 * @throws IOException
	 */
	public function save() {
		if ($this->id > 0) {
			return update_annotation($this->id, $this->name, $this->value, $this->value_type,
				$this->owner_guid, $this->access_id);
		} else {
			$this->id = create_annotation($this->entity_guid, $this->name, $this->value,
				$this->value_type, $this->owner_guid, $this->access_id);

			if (!$this->id) {
				throw new \IOException("Unable to save new " . get_class());
			}

			return $this->id;
		}
	}

	/**
	 * Delete the annotation.
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

		$qb = \Elgg\Database\Delete::fromTable('annotations');
		$qb->where($qb->compare('id', '=', $this->id, ELGG_VALUE_INTEGER));
		$deleted = _elgg_services()->db->deleteData($qb);

		if ($deleted) {
			elgg_delete_river(['annotation_id' => $this->id, 'limit' => false]);
		}

		return $deleted;
	}

	/**
	 * Disable the annotation.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function disable() {
		if ($this->enabled == 'no') {
			return true;
		}

		if (!$this->canEdit()) {
			return false;
		}

		if (!elgg_trigger_event('disable', $this->getType(), $this)) {
			return false;
		}

		$qb = \Elgg\Database\Update::table('annotations');
		$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING))
			->where($qb->compare('id', '=', $this->id, ELGG_VALUE_INTEGER));

		if ($this->getDatabase()->updateData($qb, true)) {
			$this->enabled = 'no';

			return true;
		}

		return false;
	}

	/**
	 * Enable the annotation.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function enable() {
		if ($this->enabled == 'yes') {
			return true;
		}

		if (!$this->canEdit()) {
			return false;
		}

		if (!elgg_trigger_event('enable', $this->getType(), $this)) {
			return false;
		}

		$qb = \Elgg\Database\Update::table('annotations');
		$qb->set('enabled', $qb->param('yes', ELGG_VALUE_STRING))
			->where($qb->compare('id', '=', $this->id, ELGG_VALUE_INTEGER));

		if ($this->getDatabase()->updateData($qb, true)) {
			$this->enabled = 'yes';

			return true;
		}

		return false;
	}

	/**
	 * Determines whether or not the user can edit this annotation
	 *
	 * @param int $user_guid The GUID of the user (defaults to currently logged in user)
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canEdit($user_guid = 0) {
		$entity = $this->getEntity();

		return _elgg_services()->userCapabilities->canEditAnnotation($entity, $user_guid, $this);
	}

	// SYSTEM LOG INTERFACE

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id An annotation ID.
	 *
	 * @return \ElggAnnotation
	 */
	public function getObjectFromID($id) {
		return elgg_get_annotation_from_id($id);
	}
}
