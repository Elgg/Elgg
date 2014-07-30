<?php
namespace Elgg;


class WidgetsServiceTest extends \PHPUnit_Framework_TestCase {

	public function elgg_set_config($key, $val) {
		//do nothing, that's only for BC
	}
	
	public function testRegisterTypeParametersControl() {
		$service = new \Elgg\WidgetsService(array($this, 'elgg_set_config'));

		$this->assertFalse($service->registerType('', 'Widget name', 'Widget description'));
		$this->assertFalse($service->registerType(0, 'Widget name', 'Widget description'));
		$this->assertFalse($service->registerType(null, 'Widget name', 'Widget description'));
		$this->assertFalse($service->registerType(false, 'Widget name', 'Widget description'));
		$this->assertFalse($service->registerType('widget_type', '', 'Widget description'));
		$this->assertFalse($service->registerType('widget_type', 0, 'Widget description'));
		$this->assertFalse($service->registerType('widget_type', null, 'Widget description'));
		$this->assertFalse($service->registerType('widget_type', false, 'Widget description'));
	}
	
	/**
	 * Tests register, exists and unregisrer
	 */
	public function testCanRegisterType() {
		$service = new \Elgg\WidgetsService(array($this, 'elgg_set_config'));
		
		$this->assertFalse($service->validateType('widget_type'));
		$this->assertFalse($service->validateType('not_registered_widget'));

		$this->assertTrue($service->registerType('widget_type', 'Widget name1', 'Widget description1'));
		$this->assertTrue($service->registerType('widget_type_con', 'Widget name2', 'Widget description2', array('dashboard', 'profile')));
		$this->assertTrue($service->registerType('widget_type_mul', 'Widget name3', 'Widget description3', array('all'), true));
		$this->assertTrue($service->registerType('widget_type_con_mul', 'Widget name4', 'Widget description4', array('dashboard', 'settings'), true));
		//overwrite
		$this->assertTrue($service->registerType('widget_type_con_mul', 'Widget name5', 'Widget description5', array('dashboard', 'settings'), true));
		
		$this->assertTrue($service->validateType('widget_type'));
		$this->assertTrue($service->validateType('widget_type_con'));
		$this->assertTrue($service->validateType('widget_type_mul'));
		$this->assertTrue($service->validateType('widget_type_con_mul'));
		$this->assertFalse($service->validateType('not_registered_widget'));

		return $service;
	}

	/**
	 * @depends testCanRegisterType
	 * @param \Elgg\WidgetsService $service
	 */
	public function testRegistrationParametersPreserveContext($service) {
		
		$params = array(
			//exact, context, expected
			array(false, 'all', array('widget_type', 'widget_type_mul')),
			array(false, 'dashboard', array('widget_type', 'widget_type_mul', 'widget_type_con', 'widget_type_con_mul')),
			array(false, 'profile', array('widget_type', 'widget_type_mul', 'widget_type_con')),
			array(false, 'settings', array('widget_type', 'widget_type_mul', 'widget_type_con_mul')),
			array(true, 'all', array('widget_type', 'widget_type_mul')),
			array(true, 'dashboard', array('widget_type_con', 'widget_type_con_mul')),
			array(true, 'profile', array('widget_type_con')),
			array(true, 'settings', array('widget_type_con_mul')),
		);
		
		//is returned set of handlers the same as expected
		foreach ($params as $case) {
			list($exact, $context, $expected) = $case;
			sort($expected);
			$actual = array_keys($service->getTypes($context, $exact));
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
		
		$contexts = array('all', 'dashboard', 'profile', 'settings');
		
		foreach (array(false, true) as $exact) {
			foreach ($contexts as $context) {
				$items = $service->getTypes($context, $exact);
				foreach ($items as $handler => $item) {
					$this->assertInstanceOf('\stdClass', $item);
					$this->assertNotEmpty($handler);
					$this->assertInternalType('string', $handler);
					$this->assertArrayHasKey($handler, $resps);
					$this->assertSame($resps[$handler], $item->multiple);
				}
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
		
		$contexts = array('all', 'dashboard', 'profile', 'settings');
		
		foreach (array(false, true) as $exact) {
			foreach ($contexts as $context) {
				$items = $service->getTypes($context, $exact);
				foreach ($items as $handler => $item) {
					$this->assertInstanceOf('\stdClass', $item);
					$this->assertNotEmpty($handler);
					$this->assertInternalType('string', $handler);
					$this->assertArrayHasKey($handler, $resps);
					list($name, $desc) = $resps[$handler];
					$this->assertSame($name, $item->name);
					$this->assertSame($desc, $item->description);
				}
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
	
	//TODO get, view, create, canEditLayout, defaultWidgetsInit, createDefault, defaultWidgetsPermissionsOverride

}

