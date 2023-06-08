<?php

namespace Elgg;

/**
 * Test to be applied to all active plugins
 *
 * DO NOT RUN ON PRODUCTION
 */
abstract class PluginsIntegrationTestCase extends IntegrationTestCase {
	
	public function up() {
		parent::up();
		
		$this->createApplication([
			'isolate' => true,
		]);
	}
	
	/**
	 * Data provider to get all the active plugins in the system
	 *
	 * @return array
	 */
	public function activePluginsProvider(): array {
		$this->createApplication([
			'isolate' => true,
		]);
		
		$result = [];
		
		$plugins = elgg_get_plugins();
		foreach ($plugins as $plugin) {
			$result[] = [$plugin, $plugin->getID()];
		}
		
		return $result;
	}
}
