<?php

namespace Elgg\Plugins;

use Elgg\BaseIntegrationTestCase;

/**
 * Extend this class if you wish to run integration tests in your plugin.
 * It will skip the tests of your plugin in not active.
 *
 * DO NOT RUN ON PRODUCTION
 */
abstract class IntegrationTestCase extends BaseIntegrationTestCase {
	
	/**
	 * {@inheritdoc}
	 */
	final protected function setUp(): void {
		parent::setUp();
		
		$plugin_id = $this->getPluginID();
		if (!empty($plugin_id)) {
			$plugin = elgg_get_plugin_from_id($plugin_id);
			
			if (!$plugin || !$plugin->isActive()) {
				$this->markTestSkipped("Plugin '{$plugin_id}' isn't active, skipped test");
			}
		}
		
		$this->up();
	}
}
