<?php
/**
 * River item class.
 *
 * @package    Elgg.Core
 * @subpackage Core
 */
class ElggRiverItem
{
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

		foreach ($object as $key => $value) {
			$this->$key = $value;
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
