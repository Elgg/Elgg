<?php

/**
 * River item class
 *
 * @property-read int    $id            The unique identifier (read-only)
 * @property      int    $subject_guid  The GUID of the actor
 * @property      int    $object_guid   The GUID of the object
 * @property      int    $target_guid   The GUID of the object's container
 * @property      int    $annotation_id The ID of the annotation involved in the action
 * @property      string $type          The type of one of the entities involved in the action
 * @property      string $subtype       The subtype of one of the entities involved in the action
 * @property      string $action_type   The name of the action
 * @property      string $view          The view for displaying this river item
 * @property      int    $access_id     The visibility of the river item
 * @property      int    $posted        UNIX timestamp when the action occurred
 */
class ElggRiverItem {
	
	/**
	 * @var string[] attributes that are integers
	 */
	protected const INTEGER_ATTR_NAMES = [
		'id',
		'subject_guid',
		'object_guid',
		'target_guid',
		'annotation_id',
		'access_id',
		'posted',
	];
	
	/**
	 * Construct a river item object
	 *
	 * @param \stdClass $row (optional) object obtained from database
	 */
	public function __construct(\stdClass $row = null) {
		if (!empty($row)) {
			// build from database
			foreach ($row as $key => $value) {
				if (in_array($key, static::INTEGER_ATTR_NAMES)) {
					$this->$key = (int) $value;
				} else {
					$this->$key = $value;
				}
			}
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __set(string $name, $value) {
		$this->$name = $value;
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
	 * @return \ElggAnnotation|false
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

		return _elgg_services()->riverTable->delete($this);
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
		$params = ['item' => $this];
		return _elgg_services()->hooks->trigger('to:object', 'river_item', $params, $object);
	}
	
	/**
	 * Save the river item to the database
	 *
	 * @return bool
	 */
	public function save(): bool {
		if ($this->id) {
			// update (not supported)
			return true;
		}
		
		return (bool) _elgg_services()->riverTable->create($this);
	}
}
