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

		$settings_path = \Elgg\Application::elggDir()->getPath('engine/tests/elgg-config/settings.php');
		$config = \Elgg\Config::factory($settings_path, true);

		$this->assertEquals(elgg_get_site_url(), $config->wwwroot);
		$this->assertEquals(realpath(elgg_get_data_path()), realpath($config->dataroot));
		$this->assertEquals(realpath(elgg_get_cache_path()), realpath($config->cacheroot));
	}

}