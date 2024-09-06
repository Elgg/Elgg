<?php

namespace Elgg\Integration;


class ElggCorePluginsAPITest extends \Elgg\IntegrationTestCase {

	// \ElggPlugin
	public function testElggPluginIsValid() {
		$test_plugin = \ElggPlugin::fromId('profile');
		$this->assertTrue($test_plugin->isValid());

		// check if no exceptions are thrown
		$test_plugin->assertValid();
	}

	public function testElggPluginGetID() {
		$test_plugin = \ElggPlugin::fromId('profile');
		$this->assertEquals('profile', $test_plugin->getID());
	}

	public function testGetSettingRespectsDefaults() {
		$plugin = elgg_get_plugin_from_id('profile');
		if (!$plugin) {
			$this->markTestSkipped();
		}

		$cache = _elgg_services()->metadataCache;
		$cache->save($plugin->guid, [
			new \ElggMetadata((object) [
				'name' => __METHOD__,
				'value' => 'foo',
				'entity_guid' => $plugin->guid,
			]),
		]);

		$this->assertEquals('foo', $plugin->getSetting(__METHOD__, 'bar'));
		$plugin->unsetSetting(__METHOD__);
		$this->assertEquals('bar', $plugin->getSetting(__METHOD__, 'bar'));
	}
}
