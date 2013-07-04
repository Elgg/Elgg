<?php
/**
 * Elgg Annotations
 *
 * Annotations allow you to attach bits of information to entities. They are
 * essentially the same as metadata, but with additional helper functions for
 * performing calculations.
 *
 * @internal Annotations are stored in the annotations table.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Annotations
 */
class ElggAnnotation extends ElggExtender {

	/**
	 * (non-PHPdoc)
	 *
	 * @see ElggData::initializeAttributes()
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
	 * See ElggEntity for its API for adding annotations.
	 *
	 * @param stdClass $row Database row as stdClass object
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		if (!empty($row)) {
			// Create from db row
			if ($row instanceof stdClass) {
				$annotation = $row;

				$objarray = (array) $annotation;
				foreach ($objarray as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else {
				// get an ElggAnnotation object and copy its attributes
				elgg_deprecated_notice('Passing an ID to constructor is deprecated. Use elgg_get_annotation_from_id()', 1.9);
				$annotation = elgg_get_annotation_from_id($row);
				$this->attributes = $annotation->attributes;
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
				throw new IOException("Unable to save new " . get_class());
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
		$result = _elgg_delete_metastring_based_object_by_id($this->id, 'annotation');
		if ($result) {
			elgg_delete_river(array('annotation_id' => $this->id));
		}

		return $result;
	}

	/**
	 * Disable the annotation.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function disable() {
		return _elgg_set_metastring_based_object_enabled_by_id($this->id, 'no', 'annotations');
	}

	/**
	 * Enable the annotation.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function enable() {
		return _elgg_set_metastring_based_object_enabled_by_id($this->id, 'yes', 'annotations');
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

		if ($user_guid) {
			$user = get_user($user_guid);
			if (!$user) {
				return false;
			}
		} else {
			$user = elgg_get_logged_in_user_entity();
			$user_guid = elgg_get_logged_in_user_guid();
		}

		$result = false;
		if ($user) {
			// If the owner of annotation is the specified user, they can edit.
			if ($this->getOwnerGUID() == $user_guid) {
				$result = true;
			}

			// If the user can edit the entity this is attached to, they can edit.
			$entity = $this->getEntity();
			if ($result == false && $entity->canEdit($user->getGUID())) {
				$result = true;
			}
		}

		// Trigger plugin hook - note that $user may be null
		$params = array('entity' => $entity, 'user' => $user, 'annotation' => $this);
		return elgg_trigger_plugin_hook('permissions_check', 'annotation', $params, $result);
	}

	// SYSTEM LOG INTERFACE

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id An annotation ID.
	 *
	 * @return ElggAnnotation
	 */
	public function getObjectFromID($id) {
		return elgg_get_annotation_from_id($id);
	}
}
