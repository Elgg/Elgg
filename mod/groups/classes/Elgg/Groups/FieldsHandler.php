<?php

namespace Elgg\Groups;

/**
 * Event callback for fields
 *
 * @since 4.0
 */
class FieldsHandler {

	/**
	 * Returns fields config for groups
	 *
	 * @param \Elgg\Event $event 'fields' 'group:group'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$return = (array) $event->getValue();

		$return[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('groups:description'),
			'name' => 'description',
		];
		$return[] = [
			'#type' => 'text',
			'#label' => elgg_echo('groups:briefdescription'),
			'name' => 'briefdescription',
		];
		$return[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('groups:interests'),
			'name' => 'interests',
		];
		
		return $return;
	}
}
