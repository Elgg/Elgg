<?php

namespace Elgg\WebServices\Forms;

/**
 * Prepare the fields for the webservices/api_key/edit form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'webservices/api_key/edit'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'title' => '',
			'description' => '',
			'guid' => null,
		];
		
		$entity = elgg_extract('entity', $vars);
		if ($entity instanceof \ElggApiKey) {
			// load current api key values
			foreach (array_keys($values) as $field) {
				if (isset($entity->$field)) {
					$values[$field] = $entity->$field;
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
