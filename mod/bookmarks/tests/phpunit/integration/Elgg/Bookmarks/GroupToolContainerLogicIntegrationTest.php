<?php

namespace Elgg\Bookmarks;

use Elgg\Plugins\GroupToolContainerLogicIntegrationTestCase;

class GroupToolContainerLogicIntegrationTest extends GroupToolContainerLogicIntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function getContentType(): string {
		return 'object';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getContentSubtype(): string {
		return 'bookmarks';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'bookmarks';
	}
}
