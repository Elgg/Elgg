<?php

/**
 * River (activity) item
 *
 * @property-read int    $id             The unique identifier (read-only)
 * @property-read int    $subject_guid   The GUID of the actor
 * @property-read int    $object_guid    The GUID of the object
 * @property-read int    $target_guid    The GUID of the object's container
 * @property-read int    $result_id      ID of the result object
 * @property-read string $result_type    Type of the result object
 * @property-read string $result_subtype Subtype of the result object
 * @property-read string $action         The name of the action
 * @property-read int    $posted         UNIX timestamp when the action occurred
 * @property-read string $enabled        Is the river item enabled yes|no
 */
class ElggRiverItem {

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var int
	 */
	public $subject_guid;

	/**
	 * @var int
	 */
	public $object_guid;

	/**
	 * @var int
	 */
	public $target_guid;

	/**
	 * @var int
	 * @deprecated 3.0
	 */
	public $annotation_id;

	/**
	 * @var string
	 * @deprecated 3.0
	 */
	public $action_type;

	/**
	 * @var string
	 */
	public $action;

	/**
	 * @var int
	 * @deprecated 3.0
	 */
	public $access_id;

	/**
	 * @var string
	 * @deprecated 3.0
	 */
	public $view;

	/**
	 * @var int
	 */
	public $posted;

	/**
	 * @var string
	 */
	public $enabled;

	/**
	 * @var int
	 */
	public $result_id;

	/**
	 * @var string
	 */
	public $result_type;

	/**
	 * @var string
	 */
	public $result_subtype;

	/**
	 * Construct a river item object given a database row.
	 *
	 * @param stdClass $row Database row
	 */
	public function __construct($row) {
		if (!$row instanceof stdClass) {
			throw new \InvalidArgumentException(__METHOD__ . " expects a database row object");
		}

		// the casting is to support typed serialization like json
		$int_types = ['id', 'subject_guid', 'object_guid', 'target_guid', 'annotation_id', 'access_id', 'posted'];
		foreach ($row as $key => $value) {
			if (in_array($key, $int_types)) {
				$this->$key = (int) $value;
			} else {
				$this->$key = $value;
				if ($key == 'action_type') {
					$this->action = $value;
				}
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
				elgg_deprecated_notice(
					'River items no longer have "type" and "subtype" properties. 
					Retrieve object or result and determine the type and subtype you expect.',
					'3.0'
				);
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
	 * @return ElggEntity|false
	 */
	public function getSubjectEntity() {
		return get_entity($this->subject_guid);
	}

	/**
	 * Get the object of this river item
	 *
	 * @return ElggEntity|false
	 */
	public function getObjectEntity() {
		return get_entity($this->object_guid);
	}

	/**
	 * Get the target of this river item
	 *
	 * @return ElggEntity|false
	 */
	public function getTargetEntity() {
		if ($this->target_guid) {
			return get_entity($this->target_guid);
		}

		$object = $this->getObjectEntity();
		if ($object) {
			return $object->getContainerEntity();
		}

		return false;
	}

	/**
	 * Get the result object of the river item
	 * @return ElggEntity|ElggData|false
	 */
	public function getResult() {
		if (!$this->result_id) {
			return $this->getObjectEntity();
		}

		switch ($this->result_type) {
			case 'object' :
			case 'user' :
			case 'group' :
			case 'site' :
				return get_entity($this->result_id);

			case 'annotation' :
				return elgg_get_annotation_from_id($this->result_id);

			case 'metadata' :
				return elgg_get_metadata_from_id($this->result_id);

			case 'relationship' :
				return get_relationship($this->result_id);

			case 'access_collection' :
				return get_access_collection($this->result_id);

			default :
				return false;
		}
	}

	/**
	 * Get the Annotation for this river item
	 *
	 * @return \ElggAnnotation
	 * @deprecated 3.0
	 */
	public function getAnnotation() {
		elgg_deprecated_notice(
			__METHOD__ . ' is deprecated. Use ElggRiverItem::getResult()',
			'3.0'
		);

		return elgg_get_annotation_from_id($this->annotation_id);
	}

	/**
	 * Get the view used to display this river item
	 *
	 * @return string
	 * @deprecated 3.0
	 */
	public function getView() {
		elgg_deprecated_notice(
			__METHOD__ . ' is deprecated. River items now longer use views',
			'3.0'
		);

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
	 * @tip   Can be overridden by registering for the "permissions_check:delete", "river" plugin hook.
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
	 * @param array $params Export params
	 *
	 * @return stdClass
	 */
	public function toObject(array $params = []) {
		$object = new stdClass();
		$object->id = $this->id;
		$object->subject_guid = $this->subject_guid;
		$object->object_guid = $this->object_guid;
		$object->annotation_id = $this->annotation_id;
		$object->action = $this->action;
		$object->time_posted = date('c', $this->getTimePosted());
		$object->enabled = $this->enabled;
		$object->result_id = $this->result_id;
		$object->result_type = $this->result_type;
		$object->result_subtype = $this->result_subtype;

		$params['item'] = $this;

		return _elgg_services()->hooks->trigger('to:object', 'river_item', $params, $object);
	}

}
