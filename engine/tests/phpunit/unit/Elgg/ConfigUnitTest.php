<?php

/**
 * @group Config
 * @group UnitTests
 */
class ConfigUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanReadValuesFromConfig() {

		$path = $this->getSettingsPath();
		$config = \Elgg\Config::fromFile($path);

		$this->assertEquals(elgg_get_site_url(), $config->wwwroot);
		$this->assertEquals(realpath(elgg_get_data_path()), realpath($config->dataroot));
		$this->assertEquals(realpath(elgg_get_cache_path()), realpath($config->cacheroot));
	}

	/**
	 * Tests that default memcache config is correctly populated on Travis builds
	 */
	public function testReadsMemcacheConfig() {

		if (!getenv('TRAVIS')) {
			$this->markTestSkipped('Test only runs on Travis builds');
		}

		if (class_exists('Memcache')) {
			$memcached = new Memcache();
			if ($memcached->connect('127.0.0.1', 11211) && $memcached->close()) {
				$this->assertTrue(_elgg_config()->memcache);
			}
		}
	}
}