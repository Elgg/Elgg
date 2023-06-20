<?php

namespace Elgg\Blog;

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
		return 'blog';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'blog';
	}
}
