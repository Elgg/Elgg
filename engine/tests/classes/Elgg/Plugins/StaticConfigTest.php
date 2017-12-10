<?php

namespace Elgg\Plugins;

use Elgg\UnitTestCase;

class StaticConfigTest extends UnitTestCase {

	use PluginTesting;

	/**
	 * @var \ElggPlugin
	 */
	private $plugin;

	public function up() {
       $this->plugin = $this->startPlugin(
       	ELGG_PLUGIN_INCLUDE_START |
		   ELGG_PLUGIN_IGNORE_MANIFEST |
		   ELGG_PLUGIN_REGISTER_CLASSES);
	}

	public function down() {

	}

	public function testEntityRegistration() {

		$entities = $this->plugin->getStaticConfig('entities', []);

		foreach ($entities as $entity) {
			$this->assertNotEmpty($entity['type']);
			$this->assertNotEmpty($entity['subtype']);
			if (isset($entity['class'])) {
				$this->startPlugin();
				$this->assertTrue(class_exists($entity['class']));
			}
		}
	}

	public function testActionsRegistration() {

		$actions = $this->plugin->getStaticConfig('actions', []);
		$root_path = rtrim($this->getPath(), '/');

		foreach ($actions as $action => $action_spec) {
			$this->assertInternalType('array', $action_spec);

			$filename = elgg_extract('filename', $action_spec, "$root_path/actions/{$action}.php");
			$this->assertFileExists($filename);
		}
	}

}