<?php

namespace Elgg\Traits\Notifications;

/**
 * Allow events to be (un)serialized
 *
 * @internal
 */
trait EventSerialization {
	
	/**
	 * Serializes event object for database storage
	 *
	 * @return array
	 */
	public function __serialize() {
		$data = [];
		
		$data['action'] = $this->action;
		if ($this->object instanceof \ElggData) {
			if ($this->object instanceof \ElggEntity) {
				$data['object_id'] = $this->object->guid;
			} else {
				$data['object_id'] = $this->object->id;
			}
			
			$data['object_type'] = $this->object->getType();
			$data['object_subtype'] = $this->object->getSubtype();
		}
		
		if ($this->actor) {
			$data['actor_guid'] = $this->actor->guid;
		}
		
		return $data;
	}

	/**
	 * Unserializes the event object stored in the database
	 *
	 * @param array $data serialized data
	 *
	 * @return void
	 */
	public function __unserialize($data) {
		if (isset($data['action'])) {
			$this->action = $data['action'];
		}
		
		if (isset($data['object_id']) && isset($data['object_type'])) {
			$object_id = $data['object_id'];
			switch ($data['object_type']) {
				case 'object':
				case 'user':
				case 'group':
				case 'site':
					$this->object = get_entity($object_id);
					break;
				case 'annotation':
					$this->object = elgg_get_annotation_from_id($object_id);
					break;
				case 'metadata':
					$this->object = elgg_get_metadata_from_id($object_id);
					break;
				case 'relationship':
					$this->object = elgg_get_relationship($object_id);
					break;
			}
		}
		
		if (isset($data['actor_guid'])) {
			$this->actor = get_entity($data['actor_guid']);
		}
	}
}
