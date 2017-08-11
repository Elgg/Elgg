<?php

namespace Elgg\Cache;

use Elgg\UnitTestCase;

class SimpleCacheUnitTest extends UnitTestCase {

	public function testGetUrlHandlesSingleArgument() {
		$this->markTestIncomplete();
		$simpleCache = new SimpleCache();

		$view = 'view.js';
		$url = $simpleCache->getUrl($view);

		$this->assertTrue(preg_match("#default/view.js#", $url));
	}

	public function testGetUrlHandlesTwoArguments() {
		$this->markTestIncomplete();
		$simpleCache = new SimpleCache();

		$url = $simpleCache->getUrl('js', 'view.js');

		$this->assertTrue(preg_match("#default/view.js$#", $url));
	}

	public function testGetUrlHandlesTwoArgumentsWhereSecondArgHasRedundantPrefix() {
		$this->markTestIncomplete();
		$simpleCache = new SimpleCache();

		$url = $simpleCache->getUrl('js', 'js/view.js');

		$this->assertTrue(preg_match("#default/view.js$#", $url));
	}

	public function testRespectsViewAliases() {
		$this->markTestIncomplete();
	}

}
