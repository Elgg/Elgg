<?php

namespace Elgg\Integration;


use Elgg\Exceptions\PluginException;

class ElggCorePluginsAPITest extends \Elgg\IntegrationTestCase {

	protected ?\ElggPlugin $plugin = null;
	
	protected bool $initial_active = false;
		
	public function up() {
		parent::up();
		
		$plugin = \ElggPlugin::fromId('profile');
		if (!$plugin instanceof \ElggPlugin) {
			$this->markTestSkipped();
		}
		
		$this->plugin = $plugin;
		$this->initial_active = $plugin->isActive();
		
		if (!$this->initial_active) {
			try {
				$plugin->activate();
			} catch (PluginException $e) {
				$this->markTestSkipped();
			}
		}
	}
	
	public function down() {
		if (!$this->plugin instanceof \ElggPlugin) {
			return;
		}
		
		if ($this->initial_active && !$this->plugin->isActive()) {
			try {
				$this->plugin->activate();
			} catch (PluginException $e) {
				// nothing
			}
		} elseif (!$this->initial_active && $this->plugin->isActive()) {
			try {
				$this->plugin->deactivate();
			} catch (PluginException $e) {
				// nothing
			}
		}
	}

	public function testPluginActivateAltersIsActive() {
		$this->plugin->deactivate();
		$this->assertFalse($this->plugin->isActive());

		$this->plugin->activate();
		$this->assertTrue($this->plugin->isActive());

		$this->plugin->deactivate();
		$this->assertFalse($this->plugin->isActive());
	}

	public function testElggPluginIsValid() {
		$this->assertTrue($this->plugin->isValid());

		// check if no exceptions are thrown
		$this->plugin->assertValid();
	}

	public function testElggPluginGetID() {
		$this->assertEquals('profile', $this->plugin->getID());
	}

	public function testGetSettingRespectsDefaults() {
		$cache = _elgg_services()->metadataCache;
		$cache->save($this->plugin->guid, [
			new \ElggMetadata((object) [
				'name' => __METHOD__,
				'value' => 'foo',
				'entity_guid' => $this->plugin->guid,
			]),
		]);

		$this->assertEquals('foo', $this->plugin->getSetting(__METHOD__, 'bar'));
		$this->plugin->unsetSetting(__METHOD__);
		$this->assertEquals('bar', $this->plugin->getSetting(__METHOD__, 'bar'));
	}
}
