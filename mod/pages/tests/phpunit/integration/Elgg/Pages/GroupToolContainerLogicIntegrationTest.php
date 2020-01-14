<?php

namespace Elgg\Pages;

/**
 * @group Plugins
 * @group Pages
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
		return 'page';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'pages';
	}
}
