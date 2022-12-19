<?php

namespace Elgg\Forms;

/**
 * Prepare sticky form fields
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Generic sticky form fields handler
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'all'
	 *
	 * @return array|null
	 */
	public function __invoke(\Elgg\Event $event): ?array {
		if (!(bool) $event->getParam('sticky_enabled')) {
			return null;
		}
		
		$form_name = $event->getParam('sticky_form_name');
		if (empty($form_name) || !elgg_is_sticky_form($form_name)) {
			return null;
		}
		
		$ignored_fields = (array) $event->getParam('sticky_ignored_fields');
		$body_vars = $event->getValue();
		
		// merge the sticky values into the fields
		$sticky_vars = elgg_get_sticky_values($form_name);
		foreach ($sticky_vars as $key => $value) {
			if (in_array($key, $ignored_fields)) {
				// this shouldn't happen, but just in case
				continue;
			}
			
			$body_vars[$key] = $value;
		}
		
		// clear the sticky values
		elgg_clear_sticky_form($form_name);
		
		return $body_vars;
	}
}
