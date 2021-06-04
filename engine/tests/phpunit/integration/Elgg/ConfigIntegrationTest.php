<?php

namespace Elgg;

class ConfigIntegrationTest extends IntegrationTestCase {

	/**
	 * @var Config
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->config;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}

	/**
	 * @dataProvider setValueProvider
	 */
	public function testSaveGetRemoveValue($value) {
		// set
		$this->assertTrue($this->service->save('foo', $value));
		
		// get
		$this->assertEquals($value, $this->service->{'foo'});
		
		// set again to test update
		$this->assertTrue($this->service->save('foo', $value));
		
		$this->assertEquals($value, $this->service->{'foo'});
		
		// remove
		$this->assertTrue($this->service->remove('foo'));
		
		$this->assertNull($this->service->{'foo'});
	}
	
	public function setValueProvider() {
		return [
			['bar'],
			[123],
			[['bar', 123]],
			[true],
			[false],
			[null],
		];
	}
}
