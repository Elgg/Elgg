<?php

/**
 * River item class.
 *
 * @package    Elgg.Core
 * @subpackage Core
 *
 * @property-read int    $id            The unique identifier (read-only)
 * @property-read int    $subject_guid  The GUID of the actor
 * @property-read int    $object_guid   The GUID of the object
 * @property-read int    $target_guid   The GUID of the object's container
 * @property-read int    $annotation_id The ID of the annotation involved in the action
 * @property-read string $type          The type of one of the entities involved in the action
 * @property-read string $subtype       The subtype of one of the entities involved in the action
 * @property-read string $action_type   The name of the action
 * @property-read string $view          The view for displaying this river item
 * @property-read int    $access_id     The visibility of the river item
 * @property-read int    $posted        UNIX timestamp when the action occurred
 * @property-read string $enabled       Is the river item enabled yes|no
 */
class ElggRiverItem {
	public $id;
	public $subject_guid;
	public $object_guid;
	public $target_guid;
	public $annotation_id;
	public $action_type;
	public $access_id;
	public $view;
	public $posted;
	public $enabled;

	/**
	 * Construct a river item object given a database row.
	 *
	 * @param \stdClass $object Object obtained from database
	 */
	public function __construct($object) {
		if (!($object instanceof \stdClass)) {
			throw new \InvalidParameterException("Invalid input to \ElggRiverItem constructor");
		}

		// the casting is to support typed serialization like json
		$int_types = ['id', 'subject_guid', 'object_guid', 'target_guid', 'annotation_id', 'access_id', 'posted'];
		foreach ($object as $key => $value) {
			if (in_array($key, $int_types)) {
				$this->$key = (int) $value;
			} else {
				$this->$key = $value;
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		switch ($name) {
			case 'type' :
			case 'subtype' :
				$object = get_entity($this->object_guid);
				if ($object) {
					return $object->$name;
				}
				break;
		}
	}

	/**
	 * Get the subject of this river item
	 *
	 * @return \ElggEntity
	 */
	public function getSubjectEntity() {
		return get_entity($this->subject_guid);
	}

	/**
	 * Get the object of this river item
	 *
	 * @return \ElggEntity
	 */
	public function getObjectEntity() {
		return get_entity($this->object_guid);
	}

	/**
	 * Get the target of this river item
	 *
	 * @return \ElggEntity
	 */
	public function getTargetEntity() {
		return get_entity($this->target_guid);
	}

	/**
	 * Get the Annotation for this river item
	 *
	 * @return \ElggAnnotation
	 */
	public function getAnnotation() {
		return elgg_get_annotation_from_id($this->annotation_id);
	}

	/**
	 * Get the view used to display this river item
	 *
	 * @return string
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * Get the time this activity was posted
	 *
	 * @return int
	 */
	public function getTimePosted() {
		return (int) $this->posted;
	}

	/**
	 * Get the type of the object
	 *
	 * This is required for elgg_view_list_item(). All the other data types
	 * (entities, extenders, relationships) have a type/subtype.
	 *
	 * @return string 'river'
	 */
	public function getType() {
		return 'river';
	}

	/**
	 * Get the subtype of the object
	 *
	 * This is required for elgg_view_list_item().
	 *
	 * @return string 'item'
	 */
	public function getSubtype() {
		return 'item';
	}

	/**
	 * Can a user delete this river item?
	 *
	 * @tip Can be overridden by registering for the "permissions_check:delete", "river" plugin hook.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this river item should be considered deletable by the given user.
	 * @since 2.3
	 */
	public function canDelete($user_guid = 0) {
		return _elgg_services()->userCapabilities->canDeleteRiverItem($this, $user_guid);
	}

	/**
	 * Delete the river item
	 *
	 * @return bool False if the user lacks permission or the before event is cancelled
	 * @since 2.3
	 */
	public function delete() {
		if (!$this->canDelete()) {
			return false;
		}

		$events = _elgg_services()->events;
		if (!$events->triggerBefore('delete', 'river', $this)) {
			return false;
		}

		$db = _elgg_services()->db;
		$prefix = $db->prefix;
		_elgg_services()->db->deleteData("DELETE FROM {$prefix}river WHERE id = ?", [$this->id]);

		$events->triggerAfter('delete', 'river', $this);

		return true;
	}

	/**
	 * Get a plain old object copy for public consumption
	 *
	 * @return \stdClass
	 */
	public function toObject() {
		$object = new \stdClass();
		$object->id = $this->id;
		$object->subject_guid = $this->subject_guid;
		$object->object_guid = $this->object_guid;
		$object->annotation_id = $this->annotation_id;
		$object->read_access = $this->access_id;
		$object->action = $this->action_type;
		$object->time_posted = date('c', $this->getTimePosted());
		$object->enabled = $this->enabled;
		$params = ['item' => $this];
		return _elgg_services()->hooks->trigger('to:object', 'river_item', $params, $object);
	}

}
