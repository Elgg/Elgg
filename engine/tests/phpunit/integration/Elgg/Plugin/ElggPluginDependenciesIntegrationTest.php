<?php

namespace Elgg\Plugin;

use Elgg\IntegrationTestCase;
use Elgg\Exceptions\PluginException;

class ElggPluginDependenciesIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);

		_elgg_services()->logger->disable();

		$ids = [
			'test_plugin',
			'parent_plugin',
			'conflicting_plugin',
		];

		foreach ($ids as $id) {
			$plugin = \ElggPlugin::fromId($id, $this->normalizeTestFilePath('mod/'));
			$plugin->deactivate();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		$ids = [
			'test_plugin',
			'parent_plugin',
			'conflicting_plugin',
		];

		foreach ($ids as $id) {
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
				elgg_get_plugin_from_id($id)->delete();
			});
		}

		_elgg_services()->logger->enable();
	}
	
	public function testMeetsDependencies() {
		$plugin = elgg_get_plugin_from_id('test_plugin');
		
		$this->assertTrue($plugin->meetsDependencies());
	}
	
	public function testDoesntMeetDependencies() {
		$plugin = elgg_get_plugin_from_id('conflicting_plugin');
		elgg_get_plugin_from_id('parent_plugin')->activate();
		
		$this->assertFalse($plugin->meetsDependencies());
	}
	
	public function testAssertDependencies() {
		$plugin = elgg_get_plugin_from_id('test_plugin');
		
		$this->assertEmpty($plugin->assertDependencies());
	}
	
	public function testDoesntAssertDependencies() {
		$plugin = elgg_get_plugin_from_id('conflicting_plugin');
		elgg_get_plugin_from_id('parent_plugin')->activate();
		
		$this->expectException(PluginException::class);
		$plugin->assertDependencies();
	}
}
