<?php

namespace Elgg\Database;

/**
 * @group UnitTests
 */
class PluginsUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testAfterPluginLoadActiveCheckIsFree() {
		$this->markTestIncomplete();
	}

	public function testPluginActivateAltersIsActive() {
		$this->markTestIncomplete();
	}

	public function testPluginDeactivateAltersIsActive() {
		$this->markTestIncomplete();
	}

	public function testGetPluginOrderFromBootPlugins() {
		
		$plugins = $this->getNonMockedPluginService();
		
		/* @var $plugin_high_priority \ElggPlugin */
		$plugin_high_priority = $this->createObject([
			'subtype' => 'plugin',
			'title' => 'high_priority_plugin',
		]);
		$this->assertInstanceOf('ElggPlugin', $plugin_high_priority);
		
		$priority_name = \ElggPlugin::PRIORITY_SETTING_NAME;
		// because of mocking issues don't use ->setPriority()
		$plugin_high_priority->setPrivateSetting($priority_name, 100);
		
		/* @var $plugin_low_priority \ElggPlugin */
		$plugin_low_priority = $this->createObject([
			'subtype' => 'plugin',
			'title' => 'low_priority_plugin',
		]);
		$this->assertInstanceOf('ElggPlugin', $plugin_low_priority);
		
		$plugin_low_priority->setPrivateSetting($priority_name, 10);
		
		// fallback ordering is based on guid, so make sure guid order is 'wrong'
		$this->assertGreaterThan($plugin_high_priority->guid, $plugin_low_priority->guid);
		
		$bootplugins = [
			$plugin_high_priority,
			$plugin_low_priority,
		];
		
		$plugins->setBootPlugins($bootplugins);
		
		$active_plugins = $plugins->find('active');
		$this->assertNotEmpty($active_plugins);
		
		$this->assertContains($plugin_high_priority, $active_plugins);
		$this->assertContains($plugin_low_priority, $active_plugins);
		
		$correct_order = [
			$plugin_low_priority,
			$plugin_high_priority,
		];
		
		$this->assertEquals($correct_order, $active_plugins);
	}
	
	/**
	 * Get a non mocked Plugins service
	 *
	 * @return \Elgg\Database\Plugins
	 */
	protected function getNonMockedPluginService() {
		$sp = _elgg_services();
		
		$plugins = new Plugins(
			$sp->dataCache->plugins,
			$sp->db,
			$sp->session,
			$sp->events,
			$sp->translator,
			$sp->views,
			$sp->privateSettingsCache,
			$sp->config,
			$sp->systemMessages,
			$sp->request->getContextStack()
		);
		
		return $plugins;
	}
}
