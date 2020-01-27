<?php

namespace Elgg\Bookmarks;

/**
 * @group Plugins
 * @group Bookmarks
 */
class GroupToolContainerLogicIntegrationTest extends \Elgg\Plugins\GroupToolContainerLogicIntegrationTest {

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
