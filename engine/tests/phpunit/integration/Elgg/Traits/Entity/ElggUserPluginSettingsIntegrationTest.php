<?php

namespace Elgg\Traits\Entity;

class ElggUserPluginSettingsIntegrationTest extends ElggEntityPluginSettingsTestCase {

	/**
	 * {@inheritDoc}
	 */
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
	
	public function testPluginSettingsFallbackToPluginDefaults() {
		$plugin = elgg_get_plugin_from_id('test_plugin');
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
		
		// remove user value (should fallback to plugin default)
		$this->assertTrue($this->entity->removePluginSetting('test_plugin', 'user_default1'));
		$this->assertEquals($user_settings['user_default1'], $this->entity->getPluginSetting('test_plugin', 'user_default1'));
		$this->assertEquals($user_settings['user_default1'], $this->entity->getPluginSetting('test_plugin', 'user_default1', 'my_default'));
	}
}
