<?php

namespace Elgg\Database;

/**
 * @group Datalist
 */
class DatalistTest extends \Elgg\TestCase {

	public function setUp() {
		$this->setupMockServices();
		_elgg_services()->logger->disable();
	}

	public function tearDown() {
		_elgg_services()->logger->enable();
	}

	public function testCanNotSetValueIfNameExceedsMaxLength() {

		$name = str_pad('', 256, 'a');
		$this->assertFalse(datalist_set($name, 'foo'));
	}

	public function testCanSetValue() {

		$this->assertTrue(datalist_set('foo', 'bar'));
		$this->assertEquals('bar', datalist_get('foo'));

		// Reset cache and query DB
		$pool = new \Elgg\Cache\Pool\InMemory();
		_elgg_services()->datalist->setCache($pool);
		$this->assertEquals('bar', datalist_get('foo'));

	}

	public function testCanRunFunctionOnce() {

		$func = __NAMESPACE__ .'\datalist_test_20161608';

		$this->assertTrue(run_function_once($func));
		$time = datalist_get($func);
		$this->assertNotEmpty($time);

		$this->assertFalse(run_function_once($func));

		sleep(1);
		
		$this->assertTrue(run_function_once($func, time()));
		$this->assertNotEquals($time, datalist_get($func));

	}

}

function datalist_test_20161608() {}