<?php

namespace Elgg\ExternalPages\Forms;

/**
 * Prepare the fields for the external_page/edit form
 *
 * @since 7.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'external_page/edit'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		$fields = elgg()->fields->get('object', \ElggExternalPage::SUBTYPE);
		
		$values = [];
		foreach ($fields as $field) {
			$name = (string) elgg_extract('name', $field);
			$default = null;

			if ($name === 'title') {
				$default = elgg_extract('page', $vars);
			}
			
			$values[$name] = $default;
		}
		
		$page = elgg_extract('entity', $vars);
		if ($page instanceof \ElggExternalPage) {
			// load current values
			foreach (array_keys($values) as $field) {
				if (isset($page->{$field})) {
					$values[$field] = $page->{$field};
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
