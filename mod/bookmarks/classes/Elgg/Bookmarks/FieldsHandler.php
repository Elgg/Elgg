<?php

namespace Elgg\Bookmarks;

/**
 * Register fields for bookmarks
 *
 * @since 6.1
 */
class FieldsHandler {
	
	/**
	 * Register the fields for bookmarks
	 *
	 * @param \Elgg\Event $event 'fields', 'object:bookmarks'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$result = $event->getValue();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'required' => true,
			'name' => 'title',
		];
		
		$result[] = [
			'#type' => 'url',
			'#label' => elgg_echo('bookmarks:address'),
			'required' => true,
			'name' => 'address',
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
			'entity_subtype' => 'bookmarks',
		];
		
		return $result;
	}
}
