<?php

namespace Elgg\Di;

use Elgg\Exceptions\Di\FactoryUncallableException;
use Elgg\Exceptions\Di\MissingValueException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Helpers\Di\DiContainerTestObject;

/**
 * @group UnitTests
 */
class DiContainerUnitTest extends \Elgg\UnitTestCase {

	const TEST_CLASS = '\Elgg\Helpers\Di\DiContainerTestObject';

	public function up() {

	}

	public function down() {

	}

	public function getFoo(\Elgg\Di\DiContainer $di) {
		return new DiContainerTestObject($di);
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

	public function testSetFactoryLooksUncallable() {
		$di = new \Elgg\Di\DiContainer();

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('$factory must appear callable');
		$di->setFactory('foo', new \stdClass());
	}

	public function testGetFactoryUncallable() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', 'not-a-real-callable');

		$this->expectException(FactoryUncallableException::class);
		$this->expectExceptionMessage("Factory for 'foo' was uncallable: 'not-a-real-callable'");
		$di->foo;
	}

	public function testGetFactoryUncallableArray() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', array('fakeClass', 'not-a-real-callable'));

		$this->expectException(FactoryUncallableException::class);
		$this->expectExceptionMessage("Factory for 'foo' was uncallable: 'fakeClass::not-a-real-callable'");
		$di->foo;
	}

	public function testGetFactoryUncallableArrayObject() {
		$di = new \Elgg\Di\DiContainer();
		$di->setFactory('foo', array($this, 'not-a-real-callable'));
		
		$this->expectException(FactoryUncallableException::class);
		$this->expectExceptionMessage("Factory for 'foo' was uncallable: Elgg\Di\DiContainerUnitTest->not-a-real-callable");
		$di->foo;
	}

	public function testGetMissingValue() {
		$di = new \Elgg\Di\DiContainer();

		$this->expectException(MissingValueException::class);
		$this->expectExceptionMessage('Value or factory was not set for: foo');
		$di->foo;
	}

	public function testRemove() {
		$di = new \Elgg\Di\DiContainer();
		$di->setValue('foo', 'Foo');

		$this->assertSame($di, $di->remove('foo'));
		$this->assertFalse($di->has('foo'));
	}

	public function testSetClassNames() {
		$di = new \Elgg\Di\DiContainer();
		$di->setClassName('foo', self::TEST_CLASS);

		$this->assertInstanceOf(self::TEST_CLASS, $di->foo);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Class names must be valid PHP class names');
		$di->setClassName('foo', array());
	}

	public function testSettingInvalidClassNameThrows() {
		$di = new \Elgg\Di\DiContainer();

		$euro = "\xE2\x82\xAC";

		$di->setClassName('foo1', "Foo2{$euro}3");
		$di->setClassName('foo2', "\\Foo2{$euro}3");
		$di->setClassName('foo3', "Foo2{$euro}3\\Foo2{$euro}3");

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Class names must be valid PHP class names');
		$di->setClassName('foo', 'Not Valid');
	}

	public function testAccessRemovedValue() {
		$di = new \Elgg\Di\DiContainer();
		$di->setValue('foo', 'Foo');
		$di->remove('foo');

		$this->expectException(MissingValueException::class);
		$di->foo;
	}

	public function testNamesCannotEndWithUnderscore() {
		$di = new \Elgg\Di\DiContainer();

		try {
			$di->setValue('foo_', 'foo');
			$this->fail('setValue did not throw');
		} catch (InvalidArgumentException $e) {

		}

		$this->assertFalse($di->has('foo_'));

		try {
			$di->setFactory('foo_', function () {

			});
			$this->fail('setFactory did not throw');
		} catch (InvalidArgumentException $e) {

		}

		try {
			$di->remove('foo_');
			$this->fail('remove did not throw');
		} catch (InvalidArgumentException $e) {

		}

		try {
			$di->_foo;
			$this->fail('->_foo did not throw');
		} catch (MissingValueException $e) {

		}
	}
}
