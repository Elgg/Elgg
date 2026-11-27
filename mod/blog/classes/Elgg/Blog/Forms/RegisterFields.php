<?php

namespace Elgg\Blog\Forms;

/**
 * Register blog form fields
 */
class RegisterFields {
	
	/**
	 * Add a hidden empty GUID input so the preview JS can work
	 *
	 * @param \Elgg\Event $event 'form:register:fields', 'object:blog'
	 *
	 * @return array|null
	 * @since 7.1
	 */
	public static function registerGUID(\Elgg\Event $event): ?array {
		if ($event->getEntityParam() instanceof \ElggBlog) {
			// core handles the default GUID field if there is an entity
			// @see \Elgg\Forms\RegisterFields::addEntityGUID()
			return null;
		}
		
		$fields = $event->getValue();
		
		// double check that we don't add a duplicate field
		foreach ($fields as $field) {
			if (elgg_extract('name', $field) === 'guid') {
				return null;
			}
		}
		
		$fields[] = [
			'#type' => 'hidden',
			'name' => 'guid',
		];
		
		return $fields;
	}
}
