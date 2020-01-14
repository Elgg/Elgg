<?php

/**
 * @group Plugins
 * @group ElggPlugin
 */
class ElggPluginIntegrationTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var ElggPlugin[]
	 */
	private $plugins = [];

	public function up() {

	}

	public function down() {
		$plugin = elgg_get_plugin_from_id('test_plugin');
		if ($plugin) {
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($plugin) {
				$plugin->delete();
			});
		}
	}

	public function testCanSetPriority() {

		elgg_call(ELGG_IGNORE_ACCESS, function() {
			for ($i = 0; $i < 5; $i++) {
				$plugin = new ElggPlugin();
				$plugin->title = "test_plugin$i";
				$plugin->save();
				$this->plugins[] = $plugin;
			}
		});

		$last = array_pop($this->plugins);

		$this->assertTrue($last->getPriority() > 0);

		$max_priority = _elgg_get_max_plugin_priority();
		$last->setPriority($max_priority + 10);
		$this->assertEquals($max_priority, $last->getPriority());

		$last->setPriority('first');
		$this->assertEquals(1, $last->getPriority());

		$last->setPriority('+1');
		$this->assertEquals(2, $last->getPriority());

		$last->setPriority('-1');
		$this->assertEquals(1, $last->getPriority());

		$max_priority = _elgg_get_max_plugin_priority();
		$last->setPriority('last');
		$this->assertEquals($max_priority, $last->getPriority());

		elgg_call(ELGG_IGNORE_ACCESS, function() {
			foreach ($this->plugins as $plugin) {
				if ($plugin instanceof ElggEntity) {
					$plugin->delete();
				}
			}
		});
	}

	public function testCanLoadExistingPlugin() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$plugin = new ElggPlugin();
			$plugin->title = "test_plugin_x";
			$plugin->save();
	
			$loaded_plugin = ElggPlugin::fromId('test_plugin_x');
	
			$this->assertEquals($plugin->guid, $loaded_plugin->guid);
	
			$loaded_plugin->delete();
		});
	}

	public function testPersistsPropertiesOnSave() {

		$plugin = ElggPlugin::fromId('test_plugin');

		$admin = $this->getAdmin();
		_elgg_services()->session->setLoggedInUser($admin);

		$plugin->owner_guid = 2;
		$plugin->container_guid = 2;
		$plugin->access_id = ACCESS_PRIVATE;
		$plugin->foo = 'bar';

		$plugin->save();

		$site = elgg_get_site_entity();
		$this->assertEquals('test_plugin', $plugin->getID());
		$this->assertEquals('test_plugin', $plugin->getDisplayName());
		$this->assertEquals($site->guid, $plugin->owner_guid);
		$this->assertEquals($site->guid, $plugin->container_guid);
		$this->assertEquals(ACCESS_PUBLIC, $plugin->access_id);
		$this->assertEquals('bar', $plugin->foo);

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanSetAndGetProperties() {

		$plugin = ElggPlugin::fromId('test_plugin');

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($plugin) {
			$plugin->title = 'test_plugin_edited';
			$plugin->description = 'description';
			$plugin->foo = 'bar';
	
			$plugin->save();
		});
		
		$this->assertEquals('test_plugin_edited', $plugin->getMetadata('title'));
		$this->assertEquals('description', $plugin->getMetadata('description'));
		$this->assertNull($plugin->getMetadata('foo'));

		$this->assertNull($plugin->getSetting('title'));
		$this->assertNull($plugin->getSetting('description'));
		$this->assertEquals('bar', $plugin->getSetting('foo'));
	}

	public function testInvalidatesCacheOnDelete() {

		$plugin = ElggPlugin::fromId('test_plugin_y');

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($plugin) {
			$this->assertTrue($plugin->delete());
		});
		
		$this->assertNull(elgg_get_plugin_from_id('test_plugin_y'));
	}

	public function testCanSetSetting() {
		_elgg_services()->logger->disable();

		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$plugin->activate();

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
		$plugin = new ElggPlugin();

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

		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$plugin->activate();

		$this->assertTrue($plugin->setUserSetting('foo1', 'bar1'));
		$this->assertFalse($plugin->setUserSetting('foo2', ['bar1', 'bar2']));
		$this->assertTrue($plugin->setUserSetting('foo3', 'bar3'));
		$this->assertEquals('bar1', $plugin->getUserSetting('foo1'));
		$this->assertNull($plugin->getUserSetting('foo2'));

		$this->assertEquals('set1', $plugin->getUserSetting('user_default1'));

		$this->assertEquals([
			'user_default1' => 'set1',
			'foo1' => 'bar1',
			'foo3' => 'bar3',
		], $plugin->getAllUserSettings($user->guid));

		_elgg_services()->logger->enable();

		$plugin->unsetUserSetting('foo1');
		$this->assertNull($plugin->foo1);

		$plugin->unsetAllUserSettings($user->guid);

		$this->assertEquals([
			'user_default1' => 'set1',
		], $plugin->getAllUserSettings());

		_elgg_services()->session->removeLoggedInUser();
	}
	
	public function testUnsetAllUserAndPluginSettings() {
		
		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		$untouched_plugin = ElggPlugin::fromId('languages_plugin', $this->normalizeTestFilePath('mod/'));
		
		$plugin_setting_name = 'test_name';
		$plugin_setting_value = rand();
		
		// test for some private settings values
		$this->assertNotEmpty($plugin->getPriority());
		
		// feed some settings
		$this->assertTrue($plugin->setSetting($plugin_setting_name, $plugin_setting_value));
		$this->assertTrue($plugin->setUserSetting("{$plugin_setting_name}:user", $plugin_setting_value, $user->guid));
		$this->assertTrue($untouched_plugin->setSetting($plugin_setting_name, $plugin_setting_value));
		$this->assertTrue($untouched_plugin->setUserSetting("{$plugin_setting_name}:user", $plugin_setting_value, $user->guid));
		
		// check if set correctly
		$this->assertEquals($plugin_setting_value, $plugin->getSetting($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $plugin->getUserSetting("{$plugin_setting_name}:user", $user->guid));
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getSetting($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getUserSetting("{$plugin_setting_name}:user", $user->guid));
		
		// remove all settings
		$this->assertTrue($plugin->unsetAllUserAndPluginSettings());
		
		// verify
		$this->assertNull($plugin->getSetting($plugin_setting_name));
		$this->assertNull($plugin->getUserSetting("{$plugin_setting_name}:user", $user->guid));
		// verify just the one plugin settings where removed
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getSetting($plugin_setting_name));
		$this->assertEquals($plugin_setting_value, $untouched_plugin->getUserSetting("{$plugin_setting_name}:user", $user->guid));
		
		// verify other private settings still exists
		$this->assertNotEmpty($plugin->getPriority());
		
		_elgg_services()->session->removeLoggedInUser();
	}
	
	public function testUnsetAllUserAndPluginSettingsHookCallback() {
		
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		
		$calls = 0;
		$callback = function (\Elgg\Hook $hook) use (&$calls) {
			$calls++;
			
			return false;
		};
		
		elgg_register_plugin_hook_handler('remove:settings', 'plugin', $callback);
		
		$this->assertFalse($plugin->unsetAllUserAndPluginSettings());
		$this->assertEquals(1, $calls);
	}
}
