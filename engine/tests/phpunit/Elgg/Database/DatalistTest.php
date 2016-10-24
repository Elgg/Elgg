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

}

function datalist_test_20161608() {}