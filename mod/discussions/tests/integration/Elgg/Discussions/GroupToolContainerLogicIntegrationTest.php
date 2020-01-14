<?php

namespace Elgg\Discussions;

/**
 * @group Plugins
 * @group Discussions
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
		return 'discussion';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'forum';
	}
}
