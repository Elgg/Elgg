<?php

/**
 * Bookmark
 *
 * @property string $address URL of bookmark
 */
class ElggBookmark extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'bookmarks';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultFields(): array {
		$result = parent::getDefaultFields();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'name' => 'title',
			'required' => true,
		];
		
		$result[] = [
			'#type' => 'url',
			'#label' => elgg_echo('bookmarks:address'),
			'name' => 'address',
			'required' => true,
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
