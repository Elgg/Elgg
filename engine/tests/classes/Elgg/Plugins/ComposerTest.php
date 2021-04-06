<?php

namespace Elgg\Plugins;

use Elgg\UnitTestCase;

class ComposerTest extends UnitTestCase {

	use PluginTesting;

	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;

	/**
	 * @var \Elgg\Plugin\Composer
	 */
	protected $composer;

	public function up() {
		$plugin_id = $this->getPluginID();
		$plugin = \ElggPlugin::fromId($plugin_id);
		
		$this->plugin = $plugin;
		
		$this->composer = new \Elgg\Plugin\Composer($plugin);
	}

	public function down() {

	}
	
	public function testAssertPluginId() {
		$this->composer->assertPluginId();
	}
}
