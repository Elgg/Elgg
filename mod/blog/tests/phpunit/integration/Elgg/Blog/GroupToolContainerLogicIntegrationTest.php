<?php

namespace Elgg\Blog;

/**
 * @group Plugins
 * @group Blog
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
		return 'blog';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'blog';
	}
}
