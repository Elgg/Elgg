<?php

namespace Elgg;

/**
 * @group Config
 */
class ConfigTest extends \Elgg\UnitTestCase {

	public function testCanReadValuesFromConfig() {

		$config = $this->getTestingConfigArray();

		$this->assertEquals(elgg_get_site_url(), $config['wwwroot']);
		$this->assertEquals(realpath(elgg_get_data_path()), realpath($config['dataroot']));
		$this->assertEquals(realpath(elgg_get_cache_path()), realpath($config['cacheroot']));
	}
}