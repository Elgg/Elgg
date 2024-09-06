<?php

namespace Elgg\File\Forms;

/**
 * Prepare the fields for the file/upload form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'file/upload'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'container_guid' => elgg_get_page_owner_guid(),
		];
		
		$fields = elgg()->fields->get('object', 'file');
		foreach ($fields as $field) {
			$default_value = null;
			
			$name = (string) elgg_extract('name', $field);
			if (elgg_extract('#type', $field) === 'file') {
				// don't set file input values
				continue;
			}
			
			$values[$name] = $default_value;
		}
		
		$file = elgg_extract('entity', $vars);
		if ($file instanceof \ElggFile) {
			// load current file values
			foreach (array_keys($values) as $field) {
				if (isset($file->{$field})) {
					$values[$field] = $file->{$field};
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
