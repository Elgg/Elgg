<?php

namespace Elgg\Discussions;

/**
 * Register fields for discussions
 *
 * @since 6.1
 */
class FieldsHandler {
	
	/**
	 * Register the fields for discussions
	 *
	 * @param \Elgg\Event $event 'fields', 'object:discussion'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$result = $event->getValue();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'name' => 'title',
			'required' => true,
		];
		
		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('discussion:topic:description'),
			'name' => 'description',
			'required' => true,
			'editor_type' => 'simple',
		];
		
		$result[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('tags'),
			'name' => 'tags',
		];
		
		$result[] = [
			'#type' => 'select',
			'#label' => elgg_echo('discussion:topic:status'),
			'name' => 'status',
			'options_values' => [
				'open' => elgg_echo('status:open'),
				'closed' => elgg_echo('status:closed'),
			],
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access'),
			'#class' => 'discussion-access',
			'name' => 'access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'discussion',
		];
		
		return $result;
	}
}
