<?php

namespace Elgg\Forms;

/**
 * Add fields to an entity form
 *
 * @since 7.1
 */
class RegisterFields {
	
	/**
	 * Add a hidden input to the fields when an entity is provided
	 *
	 * @param \Elgg\Event $event 'form:register:fields', 'all'
	 *
	 * @return array|null
	 */
	public static function addEntityGUID(\Elgg\Event $event): ?array {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return null;
		}
		
		$fields = $event->getValue();
		
		$fields[] = [
			'#type' => 'hidden',
			'name' => 'guid',
			'value' => $entity->guid,
		];
		
		return $fields;
	}
	
	/**
	 * Add the entity to input/access fields
	 *
	 * @param \Elgg\Event $event 'form:register:fields', 'all'
	 *
	 * @return array|null
	 */
	public static function addEntityToAccessInput(\Elgg\Event $event): ?array {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return null;
		}
		
		$fields = $event->getValue();
		foreach ($fields as $index => $field) {
			if (elgg_extract('#type', $field) !== 'access') {
				continue;
			}
			
			$fields[$index]['entity'] = $entity;
		}
		
		return $fields;
	}
	
	/**
	 * Add a container input to the form fields
	 *
	 * @param \Elgg\Event $event 'forms:register:fields', '<type>:<subtype>'
	 *
	 * @return array|null
	 */
	public static function addContainerInput(\Elgg\Event $event): ?array {
		$entity_type = $event->getParam('entity_type');
		$entity_subtype = $event->getParam('entity_subtype');
		if (empty($entity_type) || empty($entity_subtype)) {
			return null;
		}
		
		$fields = $event->getValue();
		$fields[] = [
			'#type' => 'container_guid',
			'name' => 'container_guid',
			'entity_type' => $entity_type,
			'entity_subtype' => $entity_subtype,
		];
		
		return $fields;
	}
}
