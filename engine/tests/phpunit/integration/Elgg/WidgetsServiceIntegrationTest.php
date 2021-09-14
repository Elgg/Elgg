<?php

namespace Elgg;

use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggWidget
 */
class WidgetsServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggUser[]
	 */
	var $users = [];

	public function up() {
		
	}

	public function down() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			foreach ($this->users as $user) {
				$user->delete();
			}
		});
		
		elgg()->session->removeLoggedInUser();
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
	
	public function testCanEditLayoutDefaultBehaviour() {
		$this->users[] = $owner = $this->createUser();
		$this->users[] = $editor = $this->createUser();
		$this->users[] = $admin = $this->createUser();
		$admin->makeAdmin();
		
		// make sure there is no logged in user
		elgg()->session->removeLoggedInUser();
		
		$this->assertFalse(elgg_can_edit_widget_layout('random_layout'));
		
		elgg()->session->setLoggedInUser($owner);
		$this->assertFalse(elgg_can_edit_widget_layout('random_layout'));
		
		elgg_set_page_owner_guid($owner->guid);
		
		$this->assertTrue(elgg_can_edit_widget_layout('random_layout'));
		
		$this->assertFalse(elgg_can_edit_widget_layout('random_layout', $editor->guid));
		$this->assertTrue(elgg_can_edit_widget_layout('random_layout', $admin->guid));
		
		$hook = $this->registerTestingHook('permissions_check', 'widget_layout', 'Elgg\Values::getTrue');
		$this->assertTrue(elgg_can_edit_widget_layout('random_layout', $editor->guid));
		$hook->assertNumberOfCalls(1);
		$hook->assertParamBefore('context', 'random_layout');
		$hook->assertParamBefore('user', $editor);
		$hook->assertParamBefore('page_owner', $owner);
	}
}
