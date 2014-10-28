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
	public $type;
	public $subtype;
	public $action_type;
	public $access_id;
	public $view;
	public $posted;
	public $enabled;

	/**
	 * Construct a river item object given a database row.
	 *
	 * @param stdClass $object Object obtained from database
	 */
	public function __construct($object) {
		if (!($object instanceof stdClass)) {
			throw new InvalidParameterException("Invalid input to ElggRiverItem constructor");
		}

		// the casting is to support typed serialization like json
		$int_types = array('id', 'subject_guid', 'object_guid', 'target_guid', 'annotation_id', 'access_id', 'posted');
		foreach ($object as $key => $value) {
			if (in_array($key, $int_types)) {
				$this->$key = (int)$value;
			} else {
				$this->$key = $value;
			}
		}
	}

	/**
	 * Get the subject of this river item
	 *
	 * @return ElggEntity
	 */
	public function getSubjectEntity() {
		return get_entity($this->subject_guid);
	}

	/**
	 * Get the object of this river item
	 *
	 * @return ElggEntity
	 */
	public function getObjectEntity() {
		return get_entity($this->object_guid);
	}

	/**
	 * Get the target of this river item
	 *
	 * @return ElggEntity
	 */
	public function getTargetEntity() {
		return get_entity($this->target_guid);
	}

	/**
	 * Get the Annotation for this river item
	 *
	 * @return ElggAnnotation
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
	 * @deprecated 1.9 Use getTimePosted()
	 */
	public function getPostedTime() {
		elgg_deprecated_notice("ElggRiverItem::getPostedTime() deprecated in favor of getTimePosted()", 1.9);
		return (int)$this->posted;
	}

	/**
	 * Get the time this activity was posted
	 *
	 * @return int
	 */
	public function getTimePosted() {
		return (int)$this->posted;
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
	 * Get a plain old object copy for public consumption
	 *
	 * @return stdClass
	 */
	public function toObject() {
		$object = new stdClass();
		$object->id = $this->id;
		$object->subject_guid = $this->subject_guid;
		$object->object_guid = $this->object_guid;
		$object->annotation_id = $this->annotation_id;
		$object->read_access = $this->access_id;
		$object->action = $this->action_type;
		$object->time_posted = date('c', $this->getTimePosted());
		$object->enabled = $this->enabled;
		$params = array('item' => $this);
		return elgg_trigger_plugin_hook('to:object', 'river_item', $params, $object);
	}

}
