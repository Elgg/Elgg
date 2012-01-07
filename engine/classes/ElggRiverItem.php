<?php
/**
 * River item class.
 *
 * @package    Elgg.Core
 * @subpackage Core
 * 
 * @property int    $id            The unique identifier (read-only)
 * @property int    $subject_guid  The GUID of the actor
 * @property int    $object_guid   The GUID of the object
 * @property int    $annotation_id The ID of the annotation involved in the action
 * @property string $type          The type of one of the entities involved in the action
 * @property string $subtype       The subtype of one of the entities involved in the action
 * @property string $action_type   The name of the action
 * @property string $view          The view for displaying this river item
 * @property int    $access_id     The visibility of the river item
 * @property int    $posted        UNIX timestamp when the action occurred
 */
class ElggRiverItem {
	public $id;
	public $subject_guid;
	public $object_guid;
	public $annotation_id;
	public $type;
	public $subtype;
	public $action_type;
	public $access_id;
	public $view;
	public $posted;

	/**
	 * Construct a river item object given a database row.
	 *
	 * @param stdClass $object Object obtained from database
	 */
	function __construct($object) {
		if (!($object instanceof stdClass)) {
			// throw exception
		}

		// the casting is to support typed serialization like json
		$int_types = array('id', 'subject_guid', 'object_guid', 'annotation_id', 'access_id', 'posted');
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
	 */
	public function getPostedTime() {
		return (int)$this->posted;
	}

	/**
	 * Get the type of the object
	 *
	 * @return string 'river'
	 */
	public function getType() {
		return 'river';
	}

	/**
	 * Get the subtype of the object
	 *
	 * @return string 'item'
	 */
	public function getSubtype() {
		return 'item';
	}

}
