<?php

class Elgg_Di_DiContainerTest extends PHPUnit_Framework_TestCase {

	const TEST_CLASS = 'Elgg_Di_DiContainerTestObject';

	public function getFoo(Elgg_Di_DiContainer $di) {
		return new Elgg_Di_DiContainerTestObject($di);
	}

	public function testEmptyContainer() {
		$di = new Elgg_Di_DiContainer();
		$this->assertFalse($di->has('foo'));
	}

	public function testSetValue() {
		$di = new Elgg_Di_DiContainer();

		$this->assertSame($di, $di->setValue('foo', 'Foo'));
		$this->assertTrue($di->has('foo'));
		$this->assertEquals('Foo', $di->foo);
	}

	public function testSetFactoryShared() {
		$di = new Elgg_Di_DiContainer();

		$this->assertSame($di, $di->setFactory('foo', array($this, 'getFoo')));
		$this->assertTrue($di->has('foo'));
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertSame($foo1, $foo2);
	}

	public function testSetFactoryUnshared() {
		$di = new Elgg_Di_DiContainer();

		$this->assertSame($di, $di->setFactory('foo', array($this, 'getFoo'), false));
		$this->assertTrue($di->has('foo'));
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertNotSame($foo1, $foo2);
	}

	public function testContainerIsPassedToFactory() {
		$di = new Elgg_Di_DiContainer();
		$di->setFactory('foo', array($this, 'getFoo'));

		$foo = $di->foo;
		$this->assertSame($di, $foo->di);
	}

	public function testSetFactoryLooksUncallable() {
		$di = new Elgg_Di_DiContainer();

		$this->setExpectedException('InvalidArgumentException', '$factory must appear callable');
		$di->setFactory('foo', new stdClass());
	}

	public function testGetFactoryUncallable() {
		$di = new Elgg_Di_DiContainer();
		$di->setFactory('foo', 'not-a-real-callable');

		$this->setExpectedException(
			'Elgg_Di_FactoryUncallableException',
			"Factory for 'foo' was uncallable: 'not-a-real-callable'");
		$di->foo;
	}

	public function testGetMissingValue() {
		$di = new Elgg_Di_DiContainer();

		$this->setExpectedException('Elgg_Di_MissingValueException', "Value or factory was not set for: foo");
		$di->foo;
	}

	public function testRemove() {
		$di = new Elgg_Di_DiContainer();
		$di->setValue('foo', 'Foo');

		$this->assertSame($di, $di->remove('foo'));
		$this->assertFalse($di->has('foo'));
	}

	public function testSetClassNames() {
		$di = new Elgg_Di_DiContainer();
		$di->setClassName('foo', self::TEST_CLASS);

		$this->assertInstanceOf(self::TEST_CLASS, $di->foo);

		$this->setExpectedException('InvalidArgumentException', 'Class names must be valid PHP class names');
		$di->setClassName('foo', array());
	}

	public function testSettingInvalidClassNameThrows() {
		$di = new Elgg_Di_DiContainer();

		$euro = "\xE2\x82\xAC";

		$di->setClassName('foo1', "Foo2{$euro}3");

		if (version_compare(PHP_VERSION, '5.3', '>=')) {
			$di->setClassName('foo2', "\\Foo2{$euro}3");
			$di->setClassName('foo3', "Foo2{$euro}3\\Foo2{$euro}3");
		}

		$this->setExpectedException('InvalidArgumentException', 'Class names must be valid PHP class names');
		$di->setClassName('foo', 'Not Valid');
	}

	public function testAccessRemovedValue() {
		$di = new Elgg_Di_DiContainer();
		$di->setValue('foo', 'Foo');
		$di->remove('foo');

		$this->setExpectedException('Elgg_Di_MissingValueException');
		$di->foo;
	}
}

class Elgg_Di_DiContainerTestObject {
	public $di;
	public function __construct($di = null) {
		$this->di = $di;
	}
}
