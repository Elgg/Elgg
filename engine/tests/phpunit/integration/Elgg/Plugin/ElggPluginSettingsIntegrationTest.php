<?php

namespace Elgg\Plugin;

use Elgg\IntegrationTestCase;

class ElggPluginSettingsIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
	}
	
	public function testCanSetSetting() {
		_elgg_services()->logger->disable();
		
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		
		$this->assertTrue($plugin->activate());
		
		$this->assertTrue($plugin->setSetting('foo1', 'bar1'));
		$this->assertFalse($plugin->setSetting('foo2', ['bar1', 'bar2']));
		$this->assertTrue($plugin->setSetting('foo3', 'bar3'));
		$this->assertEquals('bar1', $plugin->getSetting('foo1'));
		$this->assertNull($plugin->getSetting('foo2'));
		
		$this->assertEquals('set1', $plugin->getSetting('default1'));
		
		$this->assertEquals([
			'default1' => 'set1',
			'foo1' => 'bar1',
			'foo3' => 'bar3',
			'elgg:internal:priority' => $plugin->getPriority(),
		], $plugin->getAllSettings());
		
		_elgg_services()->logger->enable();
		
		$plugin->unsetSetting('foo1');
		$this->assertNull($plugin->foo1);
		
		$plugin->unsetAllSettings();
		
		$this->assertEquals([
			'default1' => 'set1',
			'elgg:internal:priority' => $plugin->getPriority(),
		], $plugin->getAllSettings());
	}
	
	public function testCanSetSettingOnUnsavedPlugin() {
		$plugin = new \ElggPlugin();
		
		$this->assertTrue($plugin->setSetting('foo1', 'bar1'));
		$this->assertEquals('bar1', $plugin->getSetting('foo1'));
		$this->assertNull($plugin->getSetting('foo2'));
		
		$all = $plugin->getAllSettings();
		$this->assertEquals([
			'foo1' => 'bar1',
		], $all);
	}
	
	public function testCanSetUserSetting() {
		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		_elgg_services()->logger->disable();
		
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		
		$plugin->activate();
		
		$this->assertTrue($user->setPluginSetting('test_plugin', 'foo1', 'bar1'));
		$this->assertEquals('bar1', $user->getPluginSetting('test_plugin', 'foo1'));
		
		$this->assertFalse($user->setPluginSetting('test_plugin', 'foo2', ['bar1', 'bar2']));
		$this->assertEmpty($user->getPluginSetting('test_plugin', 'foo2'));
		
		$this->assertTrue($user->setPluginSetting('test_plugin', 'foo3', 'bar3'));
		
		$this->assertEquals('set1', $user->getPluginSetting('test_plugin', 'user_default1'));
		
		_elgg_services()->logger->enable();
		
		$user->removePluginSetting('test_plugin', 'foo1');
		$this->assertEmpty($user->getPluginSetting('test_plugin', 'foo1'));
		
		_elgg_services()->session->removeLoggedInUser();
	}
	
	public function testUnsetAllEntityAndPluginSettings() {
		
		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		$untouched_plugin = \ElggPlugin::fromId('languages_plugin', $this->normalizeTestFilePath('mod/'));
		
		$plugin_setting_name = 'test_name';
		$plugin_setting_value = rand();
		
		// test for some private settings values
		$this->assertNotEmpty($plugin->getPriority());
		
		// feed some settings
		$this->assertTrue($plugin->setSetting($plugin_setting_name, $plugin_setting_value));
		$this->assertTrue($user->setPluginSetting($plugin->getID(), "{$plugin_setting_name}:user", $plugin_setting_value));
		$this->assertTrue($untouched_plugin->setSetting($plugin_setting_name, $plugin_setting_value));
		$this->assertTrue($user->setPluginSetting($untouched_plugin->getID(), "{$plugin_setting_name}:user", $plugin_setting_value));
		
		// check if set correctly
		$this->assertEquals($plugin_setting_value, $plugin->getSetting($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $user->getPluginSetting($plugin->getID(), "{$plugin_setting_name}:user"));
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getSetting($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $user->getPluginSetting($untouched_plugin->getID(), "{$plugin_setting_name}:user"));
		
		// remove all settings
		$this->assertTrue($plugin->unsetAllEntityAndPluginSettings());
		
		// verify
		$this->assertNull($plugin->getSetting($plugin_setting_name));
		$this->assertEmpty($user->getPluginSetting($plugin->getID(), "{$plugin_setting_name}:user"));
		// verify just the one plugin settings where removed
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getSetting($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $user->getPluginSetting($untouched_plugin->getID(), "{$plugin_setting_name}:user"));
		
		// verify other private settings still exists
		$this->assertNotEmpty($plugin->getPriority());
		
		_elgg_services()->session->removeLoggedInUser();
	}
	
	public function testUnsetAllEntityAndPluginSettingsHookCallback() {
		
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		
		$calls = 0;
		$callback = function (\Elgg\Hook $hook) use (&$calls) {
			$calls++;
			
			return false;
		};
		
		elgg_register_plugin_hook_handler('remove:settings', 'plugin', $callback);
		
		$this->assertFalse($plugin->unsetAllEntityAndPluginSettings());
		$this->assertEquals(1, $calls);
	}
}
