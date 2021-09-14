<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class WidgetsServiceUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function elgg_set_config($key, $val) {
		//do nothing, that's only for BC
	}

	public function testRegisterTypeParametersControl() {
		$service = new \Elgg\WidgetsService(array($this, 'elgg_set_config'));

		$definition = \Elgg\WidgetDefinition::factory([
					'id' => 'widget_test',
					'name' => 'Widget name',
		]);

		$this->assertTrue($service->registerType($definition));

		$definition->id = null;
		$this->assertFalse($service->registerType($definition));
	}

	/**
	 * Tests register, exists, unregister and getAllTypes
	 */
	public function testCanRegisterType() {
		$service = new \Elgg\WidgetsService(array($this, 'elgg_set_config'));

		$this->assertFalse($service->validateType('widget_type'));
		$this->assertFalse($service->validateType('not_registered_widget'));

		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
							'id' => 'widget_type',
							'name' => 'Widget name1',
							'description' => 'Widget description1',
		])));
		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
							'id' => 'widget_type_con',
							'name' => 'Widget name2',
							'description' => 'Widget description2',
							'context' => ['dashboard', 'profile'],
		])));
		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
							'id' => 'widget_type_mul',
							'name' => 'Widget name3',
							'description' => 'Widget description3',
							'context' => ['all', 'settings'],
							'multiple' => true,
		])));
		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
							'id' => 'widget_type_con_mul',
							'name' => 'Widget name4',
							'description' => 'Widget description4',
							'context' => ['dashboard', 'settings'],
							'multiple' => true,
		])));
		//overwrite
		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
							'id' => 'widget_type_con_mul',
							'name' => 'Widget name5',
							'description' => 'Widget description5',
							'context' => ['dashboard', 'settings'],
							'multiple' => true,
		])));

		$this->assertTrue($service->validateType('widget_type'));
		$this->assertTrue($service->validateType('widget_type_con'));
		$this->assertTrue($service->validateType('widget_type_mul'));
		$this->assertTrue($service->validateType('widget_type_con_mul'));
		$this->assertFalse($service->validateType('not_registered_widget'));
		
		$this->assertEquals(['widget_type','widget_type_con','widget_type_mul','widget_type_con_mul'], array_keys($service->getAllTypes()));

		return $service;
	}
	
	/**
	 * Tests getNameById function
	 */
	public function testGetNameById() {
		$service = new \Elgg\WidgetsService(array($this, 'elgg_set_config'));

		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
			'id' => 'widget_type_1',
			'name' => 'Widget name 1',
			'description' => 'Widget description 1',
			'context' => 'context_1',
		])));
		
		$this->assertTrue($service->registerType(\Elgg\WidgetDefinition::factory([
			'id' => 'widget_type_2',
			'name' => 'Widget name 2',
			'description' => 'Widget description 2',
			'context' => 'context_2',
		])));
		
		$this->assertFalse($service->getNameById('unknown_type'));
		$this->assertFalse($service->getNameById('widget_type_1')); // context is required
		$this->assertEquals('Widget name 1', $service->getNameById('widget_type_1', 'context_1'));
		$this->assertEquals('Widget name 2', $service->getNameById('widget_type_2', 'context_2'));
		$this->assertFalse($service->getNameById('widget_type_2', 'context_1')); // incorrect context
	}

	/**
	 * Tests register and unregister widgets with hook
	 */
	public function testCanRegisterAndUnregisterTypeWithHook() {
		$service = new \Elgg\WidgetsService(array($this, 'elgg_set_config'));

		$this->assertFalse($service->validateType('hook_widget'));

		_elgg_services()->hooks->registerHandler('handlers', 'widgets', [$this, 'registerWidgetsHookHandler']);
		$this->assertArrayHasKey('hook_widget', $service->getTypes(['context' => 'from_hook']));

		_elgg_services()->hooks->unregisterHandler('handlers', 'widgets', [$this, 'registerWidgetsHookHandler']);
		$this->assertArrayNotHasKey('hook_widget', $service->getTypes(['context' => 'from_hook']));

		_elgg_services()->hooks->registerHandler('handlers', 'widgets', [$this, 'registerWidgetsHookHandler']);
		$this->assertArrayHasKey('hook_widget', $service->getTypes(['context' => 'from_hook']));

		_elgg_services()->hooks->registerHandler('handlers', 'widgets', [$this, 'unregisterWidgetsHookHandler']);
		$this->assertArrayNotHasKey('hook_widget', $service->getTypes(['context' => 'from_hook']));

		_elgg_services()->hooks->unregisterHandler('handlers', 'widgets', [$this, 'registerWidgetsHookHandler']);
		_elgg_services()->hooks->unregisterHandler('handlers', 'widgets', [$this, 'unregisterWidgetsHookHandler']);
	}

	/**
	 * Register a widget
	 *
	 * @param \Elgg\Hook $hook 'handlers', 'widgets'
	 *
	 * @return \Elgg\WidgetDefinition[]
	 */
	public function registerWidgetsHookHandler(\Elgg\Hook $hook) {
		$value = $hook->getValue();
		
		$value[] = \Elgg\WidgetDefinition::factory([
			'id' => 'hook_widget',
			'name' => 'hook_widget name',
			'description' => 'hook_widget description',
			'context' => 'from_hook',
		]);

		return $value;
	}

	/**
	 * Unregister a widget
	 *
	 * @param \Elgg\Hook $hook 'handlers', 'widgets'
	 *
	 * @return \Elgg\WidgetDefinition[]
	 */
	public function unregisterWidgetsHookHandler(\Elgg\Hook $hook) {
		$value = $hook->getValue();
		
		foreach ($value as $key => $widget_definition) {
			if ($widget_definition->id === 'hook_widget') {
				unset($value[$key]);
				break;
			}
		}

		return $value;
	}

	/**
	 * @depends testCanRegisterType
	 * @param \Elgg\WidgetsService $service
	 */
	public function testRegistrationParametersPreserveContext($service) {

		$params = array(
			//context, expected
			array('dashboard', array('widget_type', 'widget_type_mul', 'widget_type_con', 'widget_type_con_mul')),
			array('profile', array('widget_type', 'widget_type_mul', 'widget_type_con')),
			array('settings', array('widget_type_mul', 'widget_type_con_mul')),
			array('all', array()),
		);

		//is returned set of ids the same as expected
		foreach ($params as $case) {
			list($context, $expected) = $case;
			sort($expected);
			$actual = array_keys($service->getTypes(['context' => $context]));
			sort($actual);
			$this->assertEquals($expected, $actual);
		}

		return $service;
	}

	/**
	 * @depends testRegistrationParametersPreserveContext
	 * @param \Elgg\WidgetsService $service
	 */
	public function testRegistrationParametersPreserveMultiple($service) {

		$resps = array(
			'widget_type' => false,
			'widget_type_con' => false,
			'widget_type_mul' => true,
			'widget_type_con_mul' => true,
		);

		$contexts = array('dashboard', 'profile', 'settings');

		foreach ($contexts as $context) {
			$items = $service->getTypes(['context' => $context]);
			foreach ($items as $id => $item) {
				$this->assertInstanceOf('\Elgg\WidgetDefinition', $item);
				$this->assertNotEmpty($id);
				$this->assertIsString($id);
				$this->assertArrayHasKey($id, $resps);
				$this->assertSame($resps[$id], $item->multiple);
			}
		}

		return $service;
	}

	/**
	 * @depends testRegistrationParametersPreserveMultiple
	 * @param \Elgg\WidgetsService $service
	 */
	public function testRegistrationParametersPreserveNameDescription($service) {

		$resps = array(
			'widget_type' => array('Widget name1', 'Widget description1'),
			'widget_type_con' => array('Widget name2', 'Widget description2'),
			'widget_type_mul' => array('Widget name3', 'Widget description3'),
			'widget_type_con_mul' => array('Widget name5', 'Widget description5'),
		);

		$contexts = array('dashboard', 'profile', 'settings');

		foreach ($contexts as $context) {
			$items = $service->getTypes(['context' => $context]);
			foreach ($items as $id => $item) {
				$this->assertInstanceOf('\Elgg\WidgetDefinition', $item);
				$this->assertNotEmpty($id);
				$this->assertIsString($id);
				$this->assertArrayHasKey($id, $resps);
				list($name, $desc) = $resps[$id];
				$this->assertSame($name, $item->name);
				$this->assertSame($desc, $item->description);
			}
		}

		return $service;
	}

	/**
	 * @depends testRegistrationParametersPreserveNameDescription
	 * @param \Elgg\WidgetsService $service
	 */
	public function testCanUnregisterType($service) {

		$this->assertTrue($service->unregisterType('widget_type'));
		$this->assertTrue($service->unregisterType('widget_type_con'));
		$this->assertTrue($service->unregisterType('widget_type_mul'));
		$this->assertTrue($service->unregisterType('widget_type_con_mul'));
		$this->assertFalse($service->unregisterType('widget_not_registered'));

		$this->assertFalse($service->validateType('widget_type'));
		$this->assertFalse($service->validateType('widget_type_con'));
		$this->assertFalse($service->validateType('widget_type_mul'));
		$this->assertFalse($service->validateType('not_registered_widget'));
	}
}
