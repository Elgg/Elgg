<?php

namespace Elgg\Database;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\IntegrationTestCase;

class ConfigTableIntegrationTest extends IntegrationTestCase {

	/**
	 * @var ConfigTable
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->configTable;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}

	/**
	 * @dataProvider setValueProvider
	 */
	public function testSetGetRemoveValue($value) {
		// set
		$this->assertTrue($this->service->set('foo', $value));
		
		// get
		$this->assertEquals($value, $this->service->get('foo'));
		
		// set again to test update
		$this->assertTrue($this->service->set('foo', $value));
		
		$this->assertEquals($value, $this->service->get('foo'));
		
		// remove
		$this->assertTrue($this->service->remove('foo'));
		
		$this->assertNull($this->service->get('foo'));
	}
	
	public function setValueProvider() {
		return [
			['bar'],
			[123],
			[['bar', 123]],
			[true],
			[false],
		];
	}
	
	public function testSetNullValueThrowsException() {
		$this->expectException(InvalidArgumentException::class);
		$this->service->set('foo', null);
	}
}
