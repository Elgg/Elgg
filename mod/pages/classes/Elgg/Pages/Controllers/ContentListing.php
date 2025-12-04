<?php

namespace Elgg\Pages\Controllers;

use Elgg\Controllers\GenericContentListing;

/**
 * Content listings for pages
 *
 * @since 7.0
 */
class ContentListing extends GenericContentListing {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getListingOptions(string $page, array $options): array {
		$options = parent::getListingOptions($page, $options);
		
		$options['metadata_name_value_pairs'][] = [
			'name' => 'parent_guid',
			'value' => 0,
		];
		
		return $options;
	}
}
