<?php

namespace Elgg;

use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggWidget
 */
class WidgetsServiceIntegrationTest extends IntegrationTestCase {
	
	public function testHandlerRegistration() {
		$this->assertFalse(elgg_is_widget_type('test_handler'));
		elgg_unregister_widget_type('test_handler');
		
		elgg_register_widget_type(['id' => 'test_handler', 'context' => []]);
		$this->assertTrue(elgg_is_widget_type('test_handler'));
		
		elgg_unregister_widget_type('test_handler');
		$this->assertFalse(elgg_is_widget_type('test_handler'));
	}
	
	/**
	 * Tests if widget type is invalid if a required plugin is not active
	 */
	public function testCanGetWidgetWithInActiveRequiredPlugin() {
		elgg_register_widget_type([
			'id' => 'test_required_widget',
			'name' => 'Widget name1',
			'description' => 'Widget description1',
			'context' => ['profile'],
			'required_plugin' => 'dummy_plugin',
		]);
		
		$types = elgg_get_widget_types('profile');
		$this->assertArrayNotHasKey('test_required_widget', $types);
	}

	/**
	 * Tests if widget type is valid if a required plugin is active
	 */
	public function testCanGetWidgetWithActiveRequiredPlugin() {
		elgg_register_widget_type([
			'id' => 'test_required_widget',
			'name' => 'Widget name1',
			'description' => 'Widget description1',
			'context' => ['profile'],
			'required_plugin' => 'profile',
		]);
		
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
	
	public function testCanEditLayoutDefaultBehaviour() {
		$owner = $this->createUser();
		$editor = $this->createUser();
		$admin = $this->createUser();
		$admin->makeAdmin();
		
		// make sure there is no logged in user
		elgg()->session_manager->removeLoggedInUser();
		
		$this->assertFalse(elgg_can_edit_widget_layout('random_layout'));
		
		elgg()->session_manager->setLoggedInUser($owner);
		$this->assertFalse(elgg_can_edit_widget_layout('random_layout'));
		
		elgg_set_page_owner_guid($owner->guid);
		
		$this->assertTrue(elgg_can_edit_widget_layout('random_layout'));
		
		$this->assertFalse(elgg_can_edit_widget_layout('random_layout', $editor->guid));
		$this->assertTrue(elgg_can_edit_widget_layout('random_layout', $admin->guid));
		
		$event = $this->registerTestingEvent('permissions_check', 'widget_layout', 'Elgg\Values::getTrue');
		$this->assertTrue(elgg_can_edit_widget_layout('random_layout', $editor->guid));
		$event->assertNumberOfCalls(1);
		$event->assertParamBefore('context', 'random_layout');
		$event->assertParamBefore('user', $editor);
		$event->assertParamBefore('page_owner', $owner);
	}
}
