<?php

namespace Elgg\Notifications;

use ElggData;
use ElggEntity;
use stdClass;

/**
 * Allow events to be (un)serialized
 */
trait EventSerialization {
	
	/**
	 * Serializes event object for database storage
	 * @return string
	 */
	public function serialize() {
		$data = new stdClass();
		$data->action = $this->action;
		if ($this->object instanceof ElggData) {
			if ($this->object instanceof ElggEntity) {
				$data->object_id = $this->object->guid;
			} else {
				$data->object_id = $this->object->id;
			}
			$data->object_type = $this->object->getType();
			$data->object_subtype = $this->object->getSubtype();
		}
		if ($this->actor) {
			$data->actor_guid = $this->actor->guid;
		}
		return serialize($data);
	}

	/**
	 * Unserializes the event object stored in the database
	 *
	 * @param string $serialized Serialized string
	 * @return string
	 */
	public function unserialize($serialized) {
		$data = unserialize($serialized);
		if (isset($data->action)) {
			$this->action = $data->action;
		}
		if (isset($data->object_id) && isset($data->object_type)) {
			switch ($data->object_type) {
				case 'object' :
				case 'user' :
				case 'group' :
				case 'site' :
					$this->object = get_entity($data->object_id);
					break;
				case 'annotation' :
					$this->object = elgg_get_annotation_from_id($data->object_id);
					break;
				case 'metadata' :
					$this->object = elgg_get_metadata_from_id($data->object_id);
					break;
				case 'relationship' :
					$this->object = get_relationship($data->object_id);
			}
		}
		
		if (isset($data->actor_guid)) {
			$this->actor = get_entity($data->actor_guid);
		}
	}

}
