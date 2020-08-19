<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\Integration\ElggEntityPreloaderIntegrationTest
 */
class MockEntityPreloader20140623 extends \Elgg\EntityPreloader {
	public $preloaded;
	
	public function preload($objects, array $guid_properties) {
		$this->preloaded = $objects;
	}
}
