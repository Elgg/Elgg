<?php

namespace Elgg\Integration;

use ElggPlugin;

/**
 * Elgg Plugins Test
 *
 * @group IntegrationTests
 * @group Plugins
 */
class ElggCorePluginsAPITest extends \Elgg\IntegrationTestCase {

	public function up() {
	}

	public function down() {

	}

	// \ElggPlugin
	public function testElggPluginIsValid() {
		$test_plugin = ElggPlugin::fromId('profile');
		$this->assertTrue($test_plugin->isValid());

		// check if no exceptions are thrown
		$test_plugin->assertValid();
	}

	public function testElggPluginGetID() {
		$test_plugin = ElggPlugin::fromId('profile');
		$this->assertEquals('profile', $test_plugin->getID());
	}

	public function testGetSettingRespectsDefaults() {
		$plugin = elgg_get_plugin_from_id('profile');
		if (!$plugin) {
			$this->markTestSkipped();
		}

		$cache = _elgg_services()->privateSettingsCache;
		$cache->save($plugin->guid, [
			__METHOD__ => 'foo',
		]);

		$this->assertEquals('foo', $plugin->getSetting(__METHOD__, 'bar'));
		$plugin->unsetSetting(__METHOD__);
		$this->assertEquals('bar', $plugin->getSetting(__METHOD__, 'bar'));
	}
}
