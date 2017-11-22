<?php

namespace Elgg\Di;

/**
 * @group UnitTests
 */
class DiContainerUnitTest extends \Elgg\UnitTestCase {

	const TEST_CLASS = '\Elgg\Di\DiContainerTestObject';

	public function up() {

	}

	public function down() {

	}

	public function getFoo(\Elgg\Di\DiContainer $di) {
		return new \Elgg\Di\DiContainerTestObject($di);
	}

	public function testEmptyContainer() {
		$di = new \Elgg\Di\DiContainer();
		$this->assertFalse($di->has('foo'));
	}

	public function testSetValue() {
		$di = new \Elgg\Di\DiContainer();

		$this->assertSame($di, $di->setValue('foo', 'Foo'));
		$this->assertTrue($di->has('foo'));
		$this->assertEquals('Foo', $di->foo);
	}

	public function testSetFactoryShared() {
		$di = new \Elgg\Di\DiContainer();

		$this->assertSame($di, $di->setFactory('foo', array($this, 'getFoo')));
		$this->assertTrue($di->has('foo'));
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertSame($foo1, $foo2);
	}

	public function testSetFactoryUnshared() {
		$di = new \Elgg\Di\DiContainer();

		$this->assertSame($di, $di->setFactory('foo', array($this, 'getFoo'), false));
		$this->assertTrue($di->has('foo'));
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertNotSame($foo1, $foo2);
	}

	public function testContainerIsPassedToFactory() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', array($this, 'getFoo'));

		$foo = $di->foo;
		$this->assertSame($di, $foo->di);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage $factory must appear callable
	 */
	public function testSetFactoryLooksUncallable() {
		$di = new \Elgg\Di\DiContainer();

		$di->setFactory('foo', new \stdClass());
	}

	/**
	 * @expectedException \Elgg\Di\FactoryUncallableException
	 * @expectedExceptionMessage  Factory for 'foo' was uncallable: 'not-a-real-callable'
	 */
	public function testGetFactoryUncallable() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', 'not-a-real-callable');

		$di->foo;
	}

	/**
	 * @expectedException \Elgg\Di\FactoryUncallableException
	 * @expectedExceptionMessage Factory for 'foo' was uncallable: 'fakeClass::not-a-real-callable'
	 */
	public function testGetFactoryUncallableArray() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', array('fakeClass', 'not-a-real-callable'));

		$di->foo;
	}

	/**
	 * @expectedException \Elgg\Di\FactoryUncallableException
	 * @expectedExceptionMessage Factory for 'foo' was uncallable: Elgg\Di\DiContainerUnitTest->not-a-real-callable
	 */
	public function testGetFactoryUncallableArrayObject() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', array($this, 'not-a-real-callable'));
		$di->foo;
	}

	/**
	 * @expectedException \Elgg\Di\MissingValueException
	 * @expectedExceptionMessage Value or factory was not set for: foo
	 */
	public function testGetMissingValue() {
		$di = new \Elgg\Di\DiContainer();

		$di->foo;
	}

	public function testRemove() {
		$di = new \Elgg\Di\DiContainer();
		$di->setValue('foo', 'Foo');

		$this->assertSame($di, $di->remove('foo'));
		$this->assertFalse($di->has('foo'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Class names must be valid PHP class names
	 */
	public function testSetClassNames() {
		$di = new \Elgg\Di\DiContainer();
		$di->setClassName('foo', self::TEST_CLASS);

		$this->assertInstanceOf(self::TEST_CLASS, $di->foo);

		$di->setClassName('foo', array());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Class names must be valid PHP class names
	 */
	public function testSettingInvalidClassNameThrows() {
		$di = new \Elgg\Di\DiContainer();

		$euro = "\xE2\x82\xAC";

		$di->setClassName('foo1', "Foo2{$euro}3");
		$di->setClassName('foo2', "\\Foo2{$euro}3");
		$di->setClassName('foo3', "Foo2{$euro}3\\Foo2{$euro}3");

		$di->setClassName('foo', 'Not Valid');
	}

	/**
	 * @expectedException \Elgg\Di\MissingValueException
	 */
	public function testAccessRemovedValue() {
		$di = new \Elgg\Di\DiContainer();
		$di->setValue('foo', 'Foo');
		$di->remove('foo');

		$di->foo;
	}

	public function testNamesCannotEndWithUnderscore() {
		$di = new \Elgg\Di\DiContainer();

		try {
			$di->setValue('foo_', 'foo');
			$this->fail('setValue did not throw');
		} catch (\InvalidArgumentException $e) {

		}

		$this->assertFalse($di->has('foo_'));

		try {
			$di->setFactory('foo_', function () {

			});
			$this->fail('setFactory did not throw');
		} catch (\InvalidArgumentException $e) {

		}

		try {
			$di->remove('foo_');
			$this->fail('remove did not throw');
		} catch (\InvalidArgumentException $e) {

		}

		try {
			$di->_foo;
			$this->fail('->_foo did not throw');
		} catch (MissingValueException $e) {

		}
	}

}

class DiContainerTestObject {

	public $di;

	public function __construct($di = null) {
		$this->di = $di;
	}

}
