<?php
use Elgg\Project\Paths;

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

		$config = self::getTestingConfig();

		$this->assertEquals(elgg_get_site_url(), $config->wwwroot);
		$this->assertEquals(realpath(elgg_get_data_path()), realpath($config->dataroot));
		$this->assertEquals(realpath(elgg_get_cache_path()), realpath($config->cacheroot));
		$this->assertEquals(realpath(elgg_get_asset_path()), realpath($config->assetroot));
		$this->assertEquals(realpath(elgg_get_site_url()), realpath($config->wwwroot));
		$this->assertEquals(realpath(elgg_get_plugins_path()), realpath(Paths::project() . 'mod/'));

		$engine_path = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		$this->assertEquals(realpath(elgg_get_engine_path()), $engine_path);

		$vendor_path = dirname($engine_path) . '/vendor/';
		if (is_dir($vendor_path)) {
			$project_path = dirname($engine_path);
		} else {
			$project_path = dirname(dirname(dirname(dirname($engine_path))));
		}
		$this->assertEquals(realpath(elgg_get_root_path()), $project_path);
	}

}