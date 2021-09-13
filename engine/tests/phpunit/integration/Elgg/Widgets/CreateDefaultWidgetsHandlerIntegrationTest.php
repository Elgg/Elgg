<?php

namespace Elgg\Widgets;

use Elgg\IntegrationTestCase;

class CreateDefaultWidgetsHandlerIntegrationTest extends IntegrationTestCase {

	var $default_widgets = [];

	/**
	 * @var \ElggUser
	 */
	var $user;
	
	var $default_widgets_hook;
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		$this->user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($this->user);

		elgg_register_widget_type('test_default_widget1', 'Default Widget 1', '', ['test_context', 'bar_context']);
		elgg_register_widget_type('test_default_widget2', 'Default Widget 2', '', ['test_context', 'bar_context']);
		elgg_register_widget_type('test_default_widget3', 'Default Widget 3', '', ['test_context', 'bar_context']);
		
		$this->default_widgets = elgg_call(ELGG_IGNORE_ACCESS, function() {
			return [
				get_entity(elgg_create_widget(elgg_get_site_entity()->guid, 'test_default_widget1', 'test_context')),
				get_entity(elgg_create_widget(elgg_get_site_entity()->guid, 'test_default_widget1', 'bar_context')),
				get_entity(elgg_create_widget(elgg_get_site_entity()->guid, 'test_default_widget2', 'test_context')),
				get_entity(elgg_create_widget(elgg_get_site_entity()->guid, 'test_default_widget2', 'bar_context')),
				get_entity(elgg_create_widget(elgg_get_site_entity()->guid, 'test_default_widget3', 'test_context')),
			];
		});
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		_elgg_services()->session->removeLoggedInUser();
		
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->user->delete();
			
			foreach ($this->default_widgets as $widget) {
				$widget->delete();
			}
		});
		
		elgg_unregister_widget_type('test_default_widget1');
		elgg_unregister_widget_type('test_default_widget2');
		elgg_unregister_widget_type('test_default_widget3');
	}
	
	public function testDefaultWidgetsCreatedOnEvent() {
		elgg_register_event_handler('widgets:test', 'user', CreateDefaultWidgetsHandler::class);
		
		$test_hook = $this->registerTestingHook('get_list', 'default_widgets', function(\Elgg\Hook $hook) {
			$return = $hook->getValue();
			$return[] = [
				'widget_context' => 'test_context',
				
				'event_name' => 'widgets:test',
				'event_type' => 'user',
				'entity_type' => 'user',
				'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
			];
		
			return $return;
		});
		
		$widget_options = [
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $this->user->guid,
			'private_setting_name' => 'context',
			'private_setting_value' => 'test_context',
			'limit' => false,
		];
		
		$this->assertEmpty(elgg_count_entities($widget_options));
		
		elgg_trigger_event('widgets:test', 'user', $this->user);
		
		$test_hook->assertNumberOfCalls(1);
		
		// triggering event twice should not trigger default widgets hook twice
		elgg_trigger_event('widgets:test', 'user', $this->user);
		$test_hook->assertNumberOfCalls(1);
		
		$widgets = elgg_get_entities($widget_options);
		
		$this->assertCount(3, $widgets);
		
		$handlers = [];
		foreach ($widgets as $widget) {
			$handlers[] = $widget->handler;
		}
		
		asort($handlers);
		
		$this->assertEquals([
			'test_default_widget1',
			'test_default_widget2',
			'test_default_widget3',
		], $handlers);
	}

	public function testDefaultWidgetsNotCreatedIfWidgetsAlreadyExist() {
		elgg_register_event_handler('widgets:test2', 'user', CreateDefaultWidgetsHandler::class);
		
		$test_hook = $this->registerTestingHook('get_list', 'default_widgets', function(\Elgg\Hook $hook) {
			$return = $hook->getValue();
			$return[] = [
				'widget_context' => 'test_context',
				
				'event_name' => 'widgets:test2',
				'event_type' => 'user',
				'entity_type' => 'user',
				'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
			];
		
			return $return;
		});
		
		$widget_options = [
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $this->user->guid,
			'private_setting_name' => 'context',
			'private_setting_value' => 'test_context',
			'limit' => false,
		];
		
		$this->assertEmpty(elgg_count_entities($widget_options));
		
		$this->assertNotFalse(elgg_create_widget($this->user->guid, 'test_default_widget3', 'test_context'));
		
		$this->assertEquals(1, elgg_count_entities($widget_options));
		
		elgg_trigger_event('widgets:test2', 'user', $this->user);
		
		$test_hook->assertNumberOfCalls(1);
		
		$this->assertEquals(1, elgg_count_entities($widget_options));
	}
}
