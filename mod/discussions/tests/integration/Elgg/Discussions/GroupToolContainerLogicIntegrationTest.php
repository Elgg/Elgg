<?php

namespace Elgg\Discussions;

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
		return 'discussion';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'forum';
	}
}
