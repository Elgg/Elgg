<?php

class ElggDIContainerTest extends PHPUnit_Framework_TestCase {

	const TEST_CLASS = 'ElggDIContainerTestObject';

	public function getFoo(Elgg_DIContainer $di) {
		return new ElggDIContainerTestObject($di);
	}

	public function testEmptyContainer() {
		$di = new Elgg_DIContainer();
		$this->assertFalse($di->has('foo'));
	}

	public function testSetValue() {
		$di = new Elgg_DIContainer();

		$this->assertSame($di, $di->setValue('foo', 'Foo'));
		$this->assertTrue($di->has('foo'));
		$this->assertEquals('Foo', $di->get('foo'));
	}

	public function testSetFactoryShared() {
		$di = new Elgg_DIContainer();

		$this->assertSame($di, $di->setFactory('foo', array($this, 'getFoo')));
		$this->assertTrue($di->has('foo'));
		$foo1 = $di->get('foo');
		$foo2 = $di->get('foo');
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertSame($foo1, $foo2);
	}

	public function testSetFactoryUnshared() {
		$di = new Elgg_DIContainer();

		$this->assertSame($di, $di->setFactory('foo', array($this, 'getFoo'), false));
		$this->assertTrue($di->has('foo'));
		$foo1 = $di->get('foo');
		$foo2 = $di->get('foo');
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertNotSame($foo1, $foo2);
	}

	public function testContainerIsPassedToFactory() {
		$di = new Elgg_DIContainer();
		$di->setFactory('foo', array($this, 'getFoo'));

		$foo = $di->get('foo');
		$this->assertSame($di, $foo->di);
	}

	public function testSetFactoryLooksUncallable() {
		$di = new Elgg_DIContainer();

		$this->setExpectedException('InvalidArgumentException', '$factory must appear callable');
		$di->setFactory('foo', new stdClass());
	}

	public function testGetFactoryUncallable() {
		$di = new Elgg_DIContainer();
		$di->setFactory('foo', 'not-a-real-callable');

		$this->setExpectedException(
			'Elgg_DIContainer_FactoryUncallableException',
			"Factory for 'foo' was uncallable: 'not-a-real-callable'");
		$di->get('foo');
	}

	public function testGetMissingValue() {
		$di = new Elgg_DIContainer();

		$this->setExpectedException('Elgg_DIContainer_MissingValueException', "Value or factory was not set for: foo");
		$di->get('foo');
	}

	public function testRemove() {
		$di = new Elgg_DIContainer();
		$di->setValue('foo', 'Foo');

		$this->assertSame($di, $di->remove('foo'));
		$this->assertFalse($di->has('foo'));
	}

	public function testAccessRemovedValue() {
		$di = new Elgg_DIContainer();
		$di->setValue('foo', 'Foo');
		$di->remove('foo');

		$this->setExpectedException('Elgg_DIContainer_MissingValueException');
		$di->get('foo');
	}
}

class ElggDIContainerTestObject {
	public $di;
	public function __construct($di = null) {
		$this->di = $di;
	}
}
