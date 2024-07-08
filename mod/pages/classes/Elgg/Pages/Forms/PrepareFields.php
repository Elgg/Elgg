<?php

namespace Elgg\Pages\Forms;

/**
 * Prepare the fields for the pages/edit form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'pages/edit'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'container_guid' => elgg_get_page_owner_guid(),
		];
		
		// handle customizable fields
		$fields = elgg()->fields->get('object', 'page');
		foreach ($fields as $field) {
			$default_value = null;
			$name = elgg_extract('name', $field);
			
			$values[$name] = $default_value;
		}
		
		$page = elgg_extract('entity', $vars);
		if ($page instanceof \ElggPage) {
			foreach (array_keys($values) as $field) {
				if (isset($page->{$field})) {
					$values[$field] = $page->{$field};
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
