<?php

namespace Elgg\Blog\Controllers;

use Elgg\Controllers\GenericContentListing;

/**
 * Blog content listing controller
 *
 * @since 7.0
 */
class ContentListing extends GenericContentListing {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getListingOptions(string $page, array $options): array {
		$options = parent::getListingOptions($page, $options);
		
		$defaults = [
			'created_after' => $this?->request->getParam('lower'),
			'created_before' => $this?->request->getParam('upper'),
		];
		
		return array_merge($defaults, $options);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		$options = parent::getPageOptions($page, $options);
		
		$lower = $this?->request->getParam('lower');
		if (!empty($lower)) {
			$options['title'] .= ': ' . elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]);
		}
		
		return $options;
	}
}
