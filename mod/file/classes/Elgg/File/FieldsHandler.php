<?php

namespace Elgg\File;

/**
 * Register fields for files
 *
 * @since 6.1
 */
class FieldsHandler {
	
	/**
	 * Register the fields for files
	 *
	 * @param \Elgg\Event $event 'fields', 'object:file'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$result = $event->getValue();
		
		$result[] = [
			'#type' => 'file',
			'#label' => elgg_echo('file:file'),
			'name' => 'upload',
		];
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'name' => 'title',
		];
		
		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('description'),
			'name' => 'description',
			'editor_type' => 'simple',
		];
		
		$result[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('tags'),
			'name' => 'tags',
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access'),
			'name' => 'access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'file',
		];
		
		return $result;
	}
}
