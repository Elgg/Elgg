<?php

class ElggEntityTest extends PHPUnit_Framework_TestCase {

	/** @var ElggEntity */
	protected $obj;

	protected function setUp() {
		$this->obj = $this->getMockForAbstractClass('ElggEntity');
		$reflection = new ReflectionClass('ElggEntity');
		$method = $reflection->getMethod('initializeAttributes');
		if (method_exists($method, 'setAccessible')) {
			$method->setAccessible(true);
			$method->invokeArgs($this->obj, array());
		}
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingAndGettingAttribute() {
		$this->obj->subtype = 'foo';
		$this->assertEquals('foo', $this->obj->subtype);
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingIntegerAttributes() {
		foreach (array('access_id', 'owner_guid', 'container_guid') as $name) {
			$this->obj->$name = '77';
			$this->assertSame(77, $this->obj->$name);			
		}
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingUnsettableAttributes() {
		foreach (array('guid', 'time_updated', 'last_action') as $name) {
			$this->obj->$name = 'foo';
			$this->assertNotEquals('foo', $this->obj->$name);			
		}		
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testSettingMetadataNoDatabase() {
		$this->obj->foo = 'test';
		$this->assertEquals('test', $this->obj->foo);
		$this->obj->foo = 'overwrite';
		$this->assertEquals('overwrite', $this->obj->foo);
	}

	/**
	 * @requires PHP 5.3.2
	 */
	public function testGettingNonexistentMetadataNoDatabase() {
		$this->assertNull($this->obj->foo);
	}

}
