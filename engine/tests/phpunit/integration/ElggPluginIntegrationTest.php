<?php

class ElggPluginIntegrationTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var ElggPlugin[]
	 */
	private $plugins = [];

	public function down() {
		$plugin = elgg_get_plugin_from_id('test_plugin');
		if ($plugin) {
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($plugin) {
				$plugin->delete();
			});
		}
		
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			foreach ($this->plugins as $plugin) {
				if ($plugin instanceof \ElggEntity) {
					$plugin->delete();
				}
			}
		});
	}

	public function testCanSetPriority() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			for ($i = 0; $i < 5; $i++) {
				$plugin = new \ElggPlugin();
				$plugin->title = "test_plugin$i";
				$plugin->save();
				$this->plugins[] = $plugin;
			}
		});

		$last = array_pop($this->plugins);

		$this->assertTrue($last->getPriority() > 0);

		$max_priority = _elgg_services()->plugins->getMaxPriority();
		$last->setPriority($max_priority + 10);
		$this->assertEquals($max_priority, $last->getPriority());

		$last->setPriority('first');
		$this->assertEquals(1, $last->getPriority());

		$last->setPriority('+1');
		$this->assertEquals(2, $last->getPriority());

		$last->setPriority('-1');
		$this->assertEquals(1, $last->getPriority());

		$max_priority = _elgg_services()->plugins->getMaxPriority();
		$last->setPriority('last');
		$this->assertEquals($max_priority, $last->getPriority());
	}

	public function testCanLoadExistingPlugin() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$plugin = new \ElggPlugin();
			$plugin->title = "test_plugin_x";
			$plugin->save();
	
			$loaded_plugin = \ElggPlugin::fromId('test_plugin_x');
	
			$this->assertEquals($plugin->guid, $loaded_plugin->guid);
	
			$loaded_plugin->delete();
		});
	}

	public function testPersistsPropertiesOnSave() {
		$plugin = \ElggPlugin::fromId('test_plugin');

		$admin = $this->getAdmin();
		_elgg_services()->session_manager->setLoggedInUser($admin);

		$plugin->owner_guid = 2;
		$plugin->container_guid = 2;
		$plugin->access_id = ACCESS_PRIVATE;
		$plugin->foo = 'bar';

		$plugin->save();

		$site = elgg_get_site_entity();
		$this->assertEquals('test_plugin', $plugin->getID());
		$this->assertEquals('Test Plugin', $plugin->getDisplayName()); // should come from fallback logic, not from static config
		$this->assertEquals($site->guid, $plugin->owner_guid);
		$this->assertEquals($site->guid, $plugin->container_guid);
		$this->assertEquals(ACCESS_PUBLIC, $plugin->access_id);
		$this->assertNull($plugin->foo);
		$this->assertNull($plugin->getSetting('foo'));
		$this->assertEquals('bar', $plugin->getMetadata('foo'));
	}

	public function testCanSetAndGetProperties() {
		$plugin = \ElggPlugin::fromId('test_plugin');

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($plugin) {
			$plugin->title = 'test_plugin_edited';
			$plugin->description = 'description';
			$plugin->foo = 'bar';
	
			$plugin->save();
		});
		
		$this->assertEquals('test_plugin_edited', $plugin->getMetadata('title'));
		$this->assertEquals('description', $plugin->getMetadata('description'));
		$this->assertEquals('bar', $plugin->getMetadata('foo'));

		$this->assertNull($plugin->getSetting('title'));
		$this->assertNull($plugin->getSetting('description'));
		$this->assertNull($plugin->getSetting('foo'));
		$this->assertEquals('bar', $plugin->getMetadata('foo'));
	}

	public function testInvalidatesCacheOnDelete() {
		$plugin = \ElggPlugin::fromId('test_plugin_y');

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($plugin) {
			$this->assertTrue($plugin->delete());
		});
		
		$this->assertNull(elgg_get_plugin_from_id('test_plugin_y'));
	}
	
	public function testBootCacheFlushedOnSetPluginSetting() {
		_elgg_services()->bootCache->save('test', 'test');
		
		$this->assertEquals('test', _elgg_services()->bootCache->load('test'));
		
		\ElggPlugin::fromId('test_plugin')->setSetting('foo', 'bar');
		
		$this->assertNull(_elgg_services()->bootCache->load('test'));
	}
}
