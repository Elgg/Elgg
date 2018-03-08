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
			$ia = elgg_set_ignore_access(true);
			$plugin->delete();
			elgg_set_ignore_access($ia);
		}
	}

	public function testCanSetPriority() {

		$ia = elgg_set_ignore_access(true);

		for ($i = 0; $i < 5; $i++) {
			$plugin = new ElggPlugin();
			$plugin->title = "test_plugin$i";
			$plugin->save();
			$this->plugins[] = $plugin;
		}

		elgg_set_ignore_access($ia);

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

		$ia = elgg_set_ignore_access(true);

		foreach ($this->plugins as $plugin) {
			if ($plugin instanceof ElggEntity) {
				$plugin->delete();
			}
		}

		elgg_set_ignore_access($ia);
	}

	public function testCanLoadExistingPlugin() {
		$ia = elgg_set_ignore_access(true);

		$plugin = new ElggPlugin();
		$plugin->title = "test_plugin_x";
		$plugin->save();

		elgg_set_ignore_access($ia);

		$loaded_plugin = ElggPlugin::fromId('test_plugin_x');

		$this->assertEquals($plugin->guid, $loaded_plugin->guid);

		$loaded_plugin->delete();

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

		$ia = elgg_set_ignore_access(true);

		$plugin->title = 'test_plugin_edited';
		$plugin->description = 'description';
		$plugin->foo = 'bar';

		$plugin->save();

		elgg_set_ignore_access($ia);

		$this->assertEquals('test_plugin_edited', $plugin->getMetadata('title'));
		$this->assertEquals('description', $plugin->getMetadata('description'));
		$this->assertNull($plugin->getMetadata('foo'));

		$this->assertNull($plugin->getSetting('title'));
		$this->assertNull($plugin->getSetting('description'));
		$this->assertEquals('bar', $plugin->getSetting('foo'));
	}

	public function testInvalidatesCacheOnDelete() {

		$plugin = ElggPlugin::fromId('test_plugin_y');

		$ia = elgg_set_ignore_access(true);

		$this->assertTrue($plugin->delete());

		elgg_set_ignore_access($ia);

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
}