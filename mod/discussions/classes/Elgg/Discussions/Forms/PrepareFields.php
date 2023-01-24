<?php

namespace Elgg\Discussions\Forms;

/**
 * Prepare the fields for the discussion/save form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'discussion/save'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'title' => '',
			'description' => '',
			'status' => '',
			'access_id' => ACCESS_DEFAULT,
			'tags' => '',
			'container_guid' => elgg_get_page_owner_guid(),
			'guid' => null,
		];
		
		$discussion = elgg_extract('entity', $vars);
		if ($discussion instanceof \ElggDiscussion) {
			// load current discussion values
			foreach (array_keys($values) as $field) {
				if (isset($discussion->$field)) {
					$values[$field] = $discussion->$field;
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
