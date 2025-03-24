<?php

namespace Elgg\Plugin;

use Elgg\IntegrationTestCase;

class ElggPluginSettingsIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);
	}

	public function down() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$plugin = elgg_get_plugin_from_id('test_plugin');
			if ($plugin) {
				$plugin->delete();
			}
		});
	}
	
	public function testCanSetSetting() {
		_elgg_services()->logger->disable();
		
		$plugin = \ElggPlugin::fromId('test_plugin');
		
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
		], $plugin->getAllSettings());
		
		_elgg_services()->logger->enable();
		
		$plugin->unsetSetting('foo1');
		$this->assertNull($plugin->foo1);
		
		$plugin->unsetAllSettings();
		
		$this->assertEquals([
			'default1' => 'set1',
		], $plugin->getAllSettings());
	}
	
	public function testCanSetSettingOnUnsavedPlugin() {
		$plugin = new \ElggPlugin();
		
		$this->assertTrue($plugin->setSetting('foo1', 'bar1'));
		$this->assertNull($plugin->getSetting('foo1'));
		$this->assertEquals('bar1', $plugin->getMetadata('foo1'));
		$this->assertNull($plugin->getSetting('foo2'));
		$this->assertNull($plugin->getMetadata('foo2'));
		
		$this->assertEmpty($plugin->getAllSettings());
		$this->assertEquals([
			'foo1' => 'bar1',
		], $plugin->getAllMetadata());
	}
	
	public function testCanSetUserSetting() {
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		_elgg_services()->logger->disable();
		
		$plugin = \ElggPlugin::fromId('test_plugin');
		
		$plugin->activate();
		
		$this->assertTrue($user->setPluginSetting('test_plugin', 'foo1', 'bar1'));
		$this->assertEquals('bar1', $user->getPluginSetting('test_plugin', 'foo1'));
		
		$this->assertTrue($user->setPluginSetting('test_plugin', 'foo2', ['bar1', 'bar2']));
		$this->assertEquals(['bar1', 'bar2'], $user->getPluginSetting('test_plugin', 'foo2'));
		
		$this->assertTrue($user->setPluginSetting('test_plugin', 'foo3', 'bar3'));
		
		$this->assertEquals('set1', $user->getPluginSetting('test_plugin', 'user_default1'));
		
		_elgg_services()->logger->enable();
		
		$user->removePluginSetting('test_plugin', 'foo1');
		$this->assertEmpty($user->getPluginSetting('test_plugin', 'foo1'));
	}
	
	public function testUnsetAllEntityAndPluginSettings() {
		
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$plugin = \ElggPlugin::fromId('test_plugin');
		$untouched_plugin = \ElggPlugin::fromId('languages_plugin');
		
		$plugin_setting_name = 'test_name';
		$plugin_setting_value = rand();
		
		// test for some settings values
		$this->assertNotEmpty($plugin->getPriority());
		
		// feed some settings
		$this->assertTrue($plugin->setSetting($plugin_setting_name, $plugin_setting_value));
		$this->assertTrue($user->setPluginSetting($plugin->getID(), "{$plugin_setting_name}:user", $plugin_setting_value));
		$this->assertTrue($untouched_plugin->setSetting($plugin_setting_name, $plugin_setting_value));
		$this->assertTrue($user->setPluginSetting($untouched_plugin->getID(), "{$plugin_setting_name}:user", $plugin_setting_value));
		
		// check if set correctly
		// since the plugins aren't active we need to use the metadata functions
		$this->assertEquals($plugin_setting_value, $plugin->getMetadata($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $user->getMetadata($user->getNamespacedPluginSettingName($plugin->getID(), "{$plugin_setting_name}:user")));
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getMetadata($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $user->getMetadata($user->getNamespacedPluginSettingName($untouched_plugin->getID(), "{$plugin_setting_name}:user")));
		
		// remove all settings
		$this->assertTrue($plugin->unsetAllEntityAndPluginSettings());
		
		// verify
		$this->assertNull($plugin->getSetting($plugin_setting_name));
		$this->assertNull($plugin->getMetadata($plugin_setting_name));
		$this->assertEmpty($user->getPluginSetting($plugin->getID(), "{$plugin_setting_name}:user"));
		$this->assertEmpty($user->getMetadata($user->getNamespacedPluginSettingName($plugin->getID(), "{$plugin_setting_name}:user")));
		// verify just the one plugin settings where removed
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getMetadata($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $user->getMetadata($user->getNamespacedPluginSettingName($untouched_plugin->getID(), "{$plugin_setting_name}:user")));
		
		// verify other settings still exists
		$this->assertNotEmpty($plugin->getPriority());
	}
	
	public function testUnsetAllEntityAndPluginSettingsEventCallback() {
		
		$plugin = \ElggPlugin::fromId('test_plugin');
		
		$calls = 0;
		$callback = function (\Elgg\Event $event) use (&$calls) {
			$calls++;
			
			return false;
		};
		
		elgg_register_event_handler('remove:settings', 'plugin', $callback);
		
		$this->assertFalse($plugin->unsetAllEntityAndPluginSettings());
		$this->assertEquals(1, $calls);
	}
}
