<?php

namespace Elgg\Pages;

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
		return 'page';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getGroupToolOption(): string {
		return 'pages';
	}
}
