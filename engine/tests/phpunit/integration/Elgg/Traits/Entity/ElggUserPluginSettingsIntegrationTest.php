<?php

namespace Elgg\Traits\Entity;

use Elgg\Exceptions\PluginException;

class ElggUserPluginSettingsIntegrationTest extends PluginSettingsIntegrationTestCase {

	public function down() {
		parent::down();
		
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$plugin = elgg_get_plugin_from_id('test_plugin');
			if ($plugin) {
				$plugin->delete();
			}
		});
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
	
	public function testPluginSettingsFallbackToPluginDefaults() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		if (!$plugin->isActive()) {
			try {
				$plugin->activate();
			} catch (PluginException $e) {
				$this->markTestSkipped();
			}
		}
		
		$user_settings = $plugin->getStaticConfig('user_settings', []);
		
		$this->assertNotEmpty($user_settings);
		$this->assertIsArray($user_settings);
		$this->assertArrayHasKey('user_default1', $user_settings);
		
		// get default value from plugin
		$this->assertEquals($user_settings['user_default1'], $this->entity->getPluginSetting('test_plugin', 'user_default1'));
		$this->assertEquals($user_settings['user_default1'], $this->entity->getPluginSetting('test_plugin', 'user_default1', 'my_default'));
		
		// set to different value
		$this->assertTrue($this->entity->setPluginSetting('test_plugin', 'user_default1', 'foo'));
		$this->assertEquals('foo', $this->entity->getPluginSetting('test_plugin', 'user_default1'));
		$this->assertEquals('foo', $this->entity->getPluginSetting('test_plugin', 'user_default1', 'my_default'));
		
		// remove user value (should fall back to plugin default)
		$this->assertTrue($this->entity->removePluginSetting('test_plugin', 'user_default1'));
		$this->assertEquals($user_settings['user_default1'], $this->entity->getPluginSetting('test_plugin', 'user_default1'));
		$this->assertEquals($user_settings['user_default1'], $this->entity->getPluginSetting('test_plugin', 'user_default1', 'my_default'));
	}
}
