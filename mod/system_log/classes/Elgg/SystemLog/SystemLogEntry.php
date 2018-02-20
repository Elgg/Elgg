<?php

namespace Elgg\SystemLog;

use stdClass;

/**
 * Represents a system log entry
 *
 * @property int    $id
 * @property string $object_class
 * @property string $object_id
 * @property string $object_type
 * @property string $object_subtype
 * @property string $event
 * @property int    $performed_by_guid
 * @property int    $owner_guid
 * @property int    $access_id
 * @property string $enabled
 * @property int    $time_created
 * @property string $ip_address
 */
class SystemLogEntry {

	/**
	 * Constructor
	 *
	 * @param stdClass $row DB row
	 */
	public function __construct(stdClass $row) {
		foreach ($row as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Get entry object
	 *
	 * @return \ElggData|false
	 */
	public function getObject() {
		$class = $this->object_class;
		$id = $this->object_id;

		if (!class_exists($class)) {
			// failed autoload
			return false;
		}

		$getters = [
			\ElggAnnotation::class => 'elgg_get_annotation_from_id',
			\ElggMetadata::class => 'elgg_get_metadata_from_id',
			\ElggRelationship::class => 'get_relationship',
		];

		if (isset($getters[$class]) && is_callable($getters[$class])) {
			$object = call_user_func($getters[$class], $id);
		} else if (preg_match('~^Elgg[A-Z]~', $class)) {
			$object = get_entity($id);
		} else {
			// surround with try/catch because object could be disabled
			try {
				$object = new $class($this->object_id);

				return $object;
			} catch (\Exception $e) {
			}
		}

		if (!is_object($object) || get_class($object) !== $class) {
			return false;
		}

		return $object;
	}

}
