<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class PluginsIntegrationTest extends IntegrationTestCase {

	/**
	 * @var array
	 */
	protected $backup_plugins;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->backup_plugins = [];
		
		$plugins = $this->getAllPlugins();
		/* @var $plugin \ElggPlugin */
		foreach ($plugins as $plugin) {
			$this->backup_plugins[$plugin->guid] = [
				'priority' => $plugin->getPriority(),
				'active' => $plugin->isActive(),
				'enabled' => $plugin->isEnabled(),
			];
		}
		
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		$this->createApplication();
		
		elgg_call(ELGG_SHOW_DISABLED_ENTITIES | ELGG_IGNORE_ACCESS | ELGG_DISABLE_SYSTEM_LOG, function() {
			$plugins = $this->getAllPlugins();
			/* @var $plugin \ElggPlugin */
			foreach ($plugins as $plugin) {
				if (!isset($this->backup_plugins[$plugin->guid])) {
					// testing plugin, should be removed
					$plugin->delete();
					continue;
				}
			}
			
			$priorities = [];
			$site = elgg_get_site_entity();
			foreach ($this->backup_plugins as $guid => $settings) {
				$plugin = get_entity($guid);
				if (!$plugin instanceof \ElggPlugin) {
					continue;
				}
				
				if ($settings['enabled'] !== $plugin->isEnabled()) {
					if ($settings['enabled']) {
						$plugin->enable();
					} else {
						$plugin->disable();
					}
				}
				
				if (isset($settings['priority'])) {
					$priorities[$settings['priority']] = $plugin->getID();
				}
				
				if ($settings['active'] !== $plugin->isActive()) {
					// using relationship only and not \ElggPlugin::activate() or \ElggPlugin::deactivate() because it does to much
					if ($settings['active']) {
						_elgg_services()->relationshipsTable->add($plugin->guid, 'active_plugin', $site->guid);
					} else {
						_elgg_services()->relationshipsTable->remove($plugin->guid, 'active_plugin', $site->guid);
					}
				}
			}
			
			ksort($priorities);
			_elgg_services()->plugins->setPriorities($priorities);
		});
	}
	
	/**
	 * Fetch all plugins
	 *
	 * @return \ElggPlugin[]
	 */
	protected function getAllPlugins(): array {
		return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
			return elgg_get_entities([
				'type' => 'object',
				'subtype' => 'plugin',
				'limit' => false,
			]);
		});
	}
	
	public function testPluginOrderResetWithNewPlugins() {
		$plugins_service = _elgg_services()->plugins;
		
		$this->assertTrue($plugins_service->generateEntities());
		
		$plugins = $plugins_service->find('all');
		$this->assertNotEmpty($plugins);
		
		$original_order = [];
		foreach ($plugins as $plugin) {
			$original_order[$plugin->getPriority()] = $plugin->getID();
		}
		
		// remove the first plugin from the set
		reset($original_order);
		$first_key = key($original_order);
		$first_plugin_id = $original_order[$first_key];
		
		$first_plugin = $plugins_service->get($first_plugin_id);
		$this->assertInstanceOf(\ElggPlugin::class, $first_plugin);
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($first_plugin) {
			$this->assertTrue($first_plugin->delete());
		});
		
		// check new plugin list
		$removed_plugins = $plugins_service->find('all');
		$removed_order = [];
		foreach ($removed_plugins as $plugin) {
			$removed_order[$plugin->getPriority()] = $plugin->getID();
		}
		
		$this->assertNotEquals($original_order, $removed_order);
		$this->assertCount(count($original_order) - 1, $removed_order);
		// no reordering of plugin priorities
		$this->assertArrayNotHasKey($first_key, $removed_order);
		
		// redetect the removed plugin
		$this->assertTrue($plugins_service->generateEntities());
		
		$regenerated_plugins = $plugins_service->find('all');
		$regenerated_order = [];
		foreach ($regenerated_plugins as $plugin) {
			$regenerated_order[$plugin->getPriority()] = $plugin->getID();
		}
		
		$this->assertCount(count($original_order), $regenerated_order);
		
		// verify that reordering of priorities happened
		reset($regenerated_order);
		$first_key = key($regenerated_order);
		$this->assertEquals(1, $first_key);
		
		$expected_order = array_values($removed_order);
		$expected_order[] = $first_plugin_id;
		$this->assertEquals($expected_order, array_values($regenerated_order));
	}
	
	public function testPluginOrderResetWithDisabledPlugin() {
		$plugins_service = _elgg_services()->plugins;
		
		$this->assertTrue($plugins_service->generateEntities());
		
		$plugins = $plugins_service->find('all');
		$this->assertNotEmpty($plugins);
		
		$original_order = [];
		foreach ($plugins as $plugin) {
			$original_order[$plugin->getPriority()] = $plugin->getID();
		}
		
		// remove the first plugin from the set
		reset($original_order);
		$first_key = key($original_order);
		$first_plugin_id = $original_order[$first_key];
		
		$first_plugin = $plugins_service->get($first_plugin_id);
		$this->assertInstanceOf(\ElggPlugin::class, $first_plugin);
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($first_plugin) {
			$this->assertTrue($first_plugin->disable());
		});
		
		// check new plugin list
		$disabled_plugins = $plugins_service->find('all');
		$disabled_order = [];
		foreach ($disabled_plugins as $plugin) {
			$disabled_order[$plugin->getPriority()] = $plugin->getID();
		}
		
		$this->assertNotEquals($original_order, $disabled_order);
		$this->assertCount(count($original_order) - 1, $disabled_order);
		// no reordering of plugin priorities
		$this->assertArrayNotHasKey($first_key, $disabled_order);
		
		// reorder plugins
		$this->assertTrue($plugins_service->generateEntities());
		
		$regenerated_plugins = $plugins_service->find('all');
		$regenerated_order = [];
		foreach ($regenerated_plugins as $plugin) {
			$regenerated_order[$plugin->getPriority()] = $plugin->getID();
		}
		
		$this->assertCount(count($original_order), $regenerated_order);
		
		// verify that reordering of priorities happened
		reset($regenerated_order);
		$first_key = key($regenerated_order);
		$this->assertEquals(1, $first_key);
		
		$expected_order = array_values($disabled_order);
		$expected_order[] = $first_plugin_id;
		$this->assertEquals($expected_order, array_values($regenerated_order));
	}
}
