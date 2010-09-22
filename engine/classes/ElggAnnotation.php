<?php
/**
 * Elgg Annotations
 *
 * Annotations allow you to attach bits of information to entities.
 * They are essentially the same as metadata, but with additional
 * helper functions.
 *
 * @internal Annotations are stored in the annotations table.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Annotations
 * @link http://docs.elgg.org/DataModel/Annotations
 */
class ElggAnnotation extends ElggExtender {

	/**
	 * Construct a new annotation, optionally from a given id value or db object.
	 *
	 * @param mixed $id
	 */
	function __construct($id = null) {
		$this->attributes = array();

		if (!empty($id)) {
			if ($id instanceof stdClass) {
				$annotation = $id;
			} else {
				$annotation = get_annotation($id);
			}

			if ($annotation) {
				$objarray = (array) $annotation;

				foreach($objarray as $key => $value) {
					$this->attributes[$key] = $value;
				}

				$this->attributes['type'] = "annotation";
			}
		}
	}

	/**
	 * Class member get overloading
	 *
	 * @param string $name
	 * @return mixed
	 */
	function __get($name) {
		return $this->get($name);
	}

	/**
	 * Class member set overloading
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	function __set($name, $value) {
		return $this->set($name, $value);
	}

	/**
	 * Save this instance
	 *
	 * @return int an object id
	 */
	function save() {
		if ($this->id > 0) {
			return update_annotation($this->id, $this->name, $this->value, $this->value_type, $this->owner_guid, $this->access_id);
		} else {
			$this->id = create_annotation($this->entity_guid, $this->name, $this->value,
				$this->value_type, $this->owner_guid, $this->access_id);

			if (!$this->id) {
				throw new IOException(sprintf(elgg_echo('IOException:UnableToSaveNew'), get_class()));
			}
			return $this->id;
		}
	}

	/**
	 * Delete the annotation.
	 */
	function delete() {
		return delete_annotation($this->id);
	}

	/**
	 * Get a url for this annotation.
	 *
	 * @return string
	 */
	public function getURL() {
		return get_annotation_url($this->id);
	}

	// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 */
	public function getObjectFromID($id) {
		return get_annotation($id);
	}
}