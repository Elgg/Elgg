<?php

namespace Elgg\Integration;

use ElggWidget;
use ElggUser;
use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggWidget
 *
 * @group IntegrationTests
 */
class ElggCoreWidgetTest extends IntegrationTestCase {
	/**
	 * @var ElggWidget
	 */
	protected $widget;

	/**
	 * @var ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($this->user);
		
		$this->widget = $this->createObject([
			'subtype' => 'widget',
		], [
			'tags' => 'tag',
		]);
		_elgg_services()->session->removeLoggedInUser();
	}

	public function down() {
		$this->widget->delete();
		$this->user->delete();
	}

	public function testSettingAllowedMetadata() {
		$this->widget->title = 'test_title';
		$this->assertEquals('test_title', $this->widget->title);
		$this->assertEquals('test_title', $this->widget->getMetadata('title'));
		$this->widget->description = 'test_description';
		$this->assertEquals('test_description', $this->widget->description);
		$this->assertEquals('test_description', $this->widget->getMetadata('description'));
	}
	
	public function testSettingGuidIsNotAllowed() {
		$current_guid = $this->widget->guid;
		$this->widget->guid = 12345;
		$this->assertEquals($current_guid, $this->widget->guid);
	}
		
	public function testUnsettingMetadata() {
		$this->widget->title = 'testing';
		$this->assertEquals('testing', $this->widget->title);
		$this->assertTrue(isset($this->widget->title));
		unset($this->widget->title);
		$this->assertEmpty($this->widget->title);
		$this->assertFalse(isset($this->widget->title));
	}
		
	public function testUnsettingPrivateSettings() {
		$this->widget->order = '123';
		$this->assertEquals('123', $this->widget->getPrivateSetting('order'));
		$this->assertTrue(isset($this->widget->order));
		unset($this->widget->order);
		$this->assertEmpty($this->widget->getPrivateSetting('order'));
		$this->assertFalse(isset($this->widget->order));
	}
		
	public function testSettingContext() {
		$this->widget->setContext('my_dashboard');
		$this->assertEquals('my_dashboard', $this->widget->getContext());
	}
		
	public function testGetTitle() {
		$this->widget->title = 'get_title';
		$this->widget->handler = 'widget_handler';
		$this->widget->context = 'widget_context';
		$this->assertEquals('get_title', $this->widget->getDisplayName());
		
		unset($this->widget->title);
		
		$this->assertTrue(elgg_register_widget_type('widget_handler', 'title_from_definition', 'widget_description', ['widget_context']));
		$this->assertEquals('title_from_definition', $this->widget->getDisplayName());
	}
	
	public function testSaveSettings() {
		$params = [
			'setting_1' => 'value_1',
			'setting_1' => 'value_2',
		];
		
		$result = $this->widget->saveSettings($params);
		$this->assertFalse($result);
		
		$ia = elgg_set_ignore_access(true);
		$result = $this->widget->saveSettings($params);
		$this->assertTrue($result);
		
		foreach ($params as $name => $value) {
			$this->assertEquals($value, $this->widget->$name);
		}
		
		elgg_set_ignore_access($ia);
	}
	
	public function testHandlerRegistration() {
		$this->assertFalse(elgg_is_widget_type('test_handler'));
		$this->assertFalse(elgg_unregister_widget_type('test_handler'));
		
		$return = elgg_register_widget_type([
			'id' => 'test_handler',
		]);
		$this->assertTrue($return);
		$this->assertTrue(elgg_is_widget_type('test_handler'));
		$this->assertTrue(elgg_unregister_widget_type('test_handler'));
		
		$this->assertFalse(elgg_is_widget_type('test_handler'));
	}
	
	

}
