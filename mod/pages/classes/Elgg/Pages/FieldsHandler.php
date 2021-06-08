<?php

namespace Elgg\Pages;

use Elgg\Hook;

/**
 * Hook callback for fields
 *
 * @since 4.0
 */
class FieldsHandler {

	/**
	 * Returns fields config for pages
	 *
	 * @param \Elgg\Hook $hook 'fields' 'object:pages'
	 *
	 * @return array
	 */
	public function __invoke(Hook $hook) {
		$return = (array) $hook->getValue();

		$return[] = [
			'#type' => 'text',
			'#label' => elgg_echo('pages:title'),
			'name' => 'title',
			'required' => true,
		];
		$return[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('pages:description'),
			'name' => 'description',
		];
		$return[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('pages:tags'),
			'name' => 'tags',
		];
		$return[] = [
			'#type' => 'pages/parent',
			'#label' => elgg_echo('pages:parent_guid'),
			'name' => 'parent_guid',
		];
		$return[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access:read'),
			'name' => 'access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'page',
		];
		$return[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access:write'),
			'name' => 'write_access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'page',
			'purpose' => 'write',
			'entity_allows_comments' => false, // no access change warning for write access input
		];
		
		return $return;
	}
}
