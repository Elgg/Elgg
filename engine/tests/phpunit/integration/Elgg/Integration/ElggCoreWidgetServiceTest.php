<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggWidget
 *
 * @group IntegrationTests
 */
class ElggCoreWidgetServiceTest extends IntegrationTestCase {


	public function up() {
		
	}

	public function down() {
	
	}
	
	/**
	 * Tests if widget type is invalid if a required plugin is not active
	 */
	public function testCanGetWidgetWithInActiveRequiredPlugin() {
		
		$this->assertTrue(elgg_register_widget_type([
			'id' => 'test_required_widget',
			'name' => 'Widget name1',
			'description' => 'Widget description1',
			'context' => ['profile'],
			'required_plugin' => 'dummy_plugin',
		]));
		
		$types = elgg_get_widget_types('profile');
		$this->assertArrayNotHasKey('test_required_widget', $types);
	}

	/**
	 * Tests if widget type is valid if a required plugin is active
	 */
	public function testCanGetWidgetWithActiveRequiredPlugin() {
		$this->assertTrue(elgg_register_widget_type([
			'id' => 'test_required_widget',
			'name' => 'Widget name1',
			'description' => 'Widget description1',
			'context' => ['profile'],
			'required_plugin' => 'profile',
		]));
		
		$test_plugin = \ElggPlugin::fromId('profile');
		$should_deactivate = false;
		if (!$test_plugin->isActive()) {
			$this->assertTrue($test_plugin->activate());
			$should_deactivate = true;
		}
		
		$types = elgg_get_widget_types('profile');
		$this->assertArrayHasKey('test_required_widget', $types);
		
		if ($should_deactivate) {
			$this->assertTrue($test_plugin->deactivate());
		}
	}
}
