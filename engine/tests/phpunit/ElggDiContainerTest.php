<?php

class ElggDiContainerTest extends PHPUnit_Framework_TestCase {

	const TEST_CLASS = 'ElggDiFactoryTestObject';

	protected function getTestContainer(array $props = array()) {
		$di = new Elgg_Di_Container();
		foreach ($props as $name => $val) {
			$di->{$name} = $val;
		}
		return $di;
	}

	public function getFoo(Elgg_Di_Container $di) {
		return $di->foo;
	}

	public function testInvokerPassesContainerToCallable() {
		$di = $this->getTestContainer(array('foo' => 'Foo'));
		$obj = new Elgg_Di_Invoker(array($this, 'getFoo'));

		$foo = $obj->resolveValue($di);
		$this->assertEquals('Foo', $foo);
	}

	public function testInvokerChecksCallableInConstructor() {
		$this->setExpectedException('InvalidArgumentException');
		new Elgg_Di_Invoker(false);
	}

	public function testInvokerChecksCallableAtResolveTime() {
		$obj = new Elgg_Di_Invoker('y7r8437843');
		$this->setExpectedException('ErrorException');
		$obj->resolveValue($this->getTestContainer());
	}

	public function testReference() {
		$di1 = $this->getTestContainer(array(
			'foo' => 'Foo1',
			'bar' => new Elgg_Di_Factory(self::TEST_CLASS),
		));
		$di2 = $this->getTestContainer(array(
			'foo' => 'Foo2',
			'bar' => new Elgg_Di_Factory(self::TEST_CLASS, array('hello')),
		));

		$ref1 = new Elgg_Di_Reference('foo');
		$this->assertEquals('Foo1', $ref1->resolveValue($di1));
		$this->assertEquals('Foo2', $ref1->resolveValue($di2));

		$ref2 = new Elgg_Di_Reference('foo', $di1);
		$this->assertEquals('Foo1', $ref2->resolveValue($di1));
		$this->assertEquals('Foo1', $ref2->resolveValue($di2));

		$ref3 = new Elgg_Di_Reference('new_bar()');
		$bar1 = $ref3->resolveValue($di1);
		$bar2 = $ref3->resolveValue($di1);
		$this->assertInstanceOf(self::TEST_CLASS, $bar1);
		$this->assertInstanceOf(self::TEST_CLASS, $bar2);
		$this->assertNotSame($bar1, $bar2);

		$ref4 = new Elgg_Di_Reference('new_bar()', $di2);
		$bar = $ref4->resolveValue($di1);
		$this->assertEquals('hello', $bar->args[0]);
	}

	public function testFactoryClassAndArguments() {
		$di = $this->getTestContainer();

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$obj = $fact->resolveValue($di);
		$this->assertInstanceOf(self::TEST_CLASS, $obj);
		$this->assertEmpty($obj->args);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS, array('one', 2));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('one', 2), $obj->args);
	}

	public function testFactoryCallsSetters() {
		$di = $this->getTestContainer(array('bar' => 'Bar'));

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$fact->setSetter('setArray', array(1, 2, 3));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('setArray' => array(1, 2, 3)), $obj->calls);
	}

	public function testFactoryCanUseResolvedValues() {
		$di = $this->getTestContainer(array(
			'foo' => 'Foo',
			'bar' => 'Bar',
			'testObjClass' => self::TEST_CLASS,
			'anArray' => array(1, 2, 3),
		));

		$fact = new Elgg_Di_Factory($di->ref('testObjClass'));
		$obj = $fact->resolveValue($di);
		$this->assertInstanceOf(self::TEST_CLASS, $obj);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS, array($di->ref('foo')));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('Foo'), $obj->args);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$fact->setSetter('setBar', $di->ref('bar'));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('setBar' => 'Bar'), $obj->calls);
	}

	public function testFactoryRequiresClassNameToResolveToString() {
		$di = $this->getTestContainer(array('anArray' => array(1, 2, 3)));
		$fact = new Elgg_Di_Factory($di->ref('anArray'));

		$this->setExpectedException('ErrorException');
		$fact->resolveValue($di);
	}

	public function testContainerEmpty() {
		$di = new Elgg_Di_Container();
		$this->assertFalse(isset($di->foo));
	}

	public function testContainerSetNonResolvable() {
		$di = new Elgg_Di_Container();

		$di->foo = 'Foo';
		$this->assertTrue(isset($di->foo));
		$this->assertFalse($di->isResolvable('foo'));
	}

	public function testContainerSetResolvable() {
		$di = new Elgg_Di_Container();

		$di->foo = new Elgg_Di_Factory(self::TEST_CLASS);
		$this->assertTrue(isset($di->foo));
		$this->assertTrue($di->isResolvable('foo'));
	}

	public function testContainerGetMissingValue() {
		$di = new Elgg_Di_Container();
		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$di->foo;
	}

	public function testContainerGetNewUnresolvableValue() {
		$di = new Elgg_Di_Container();
		$di->foo = 'Foo';

		$this->setExpectedException('Elgg_Di_Exception_ValueUnresolvableException');
		$di->new_foo();
	}

	public function testContainerSetAfterRead() {
		$di = new Elgg_Di_Container();

		$di->foo = 'Foo';
		$di->foo = 'Foo2';
		$this->assertEquals('Foo2', $di->foo);
	}

	public function testContainerHandlesNullValue() {
		$di = new Elgg_Di_Container();

		$di->null = null;
		$this->assertTrue(isset($di->null));
		$this->assertNull($di->null);
	}

	public function testContainerGetResolvables() {
		$di = new Elgg_Di_Container();

		$di->foo = new Elgg_Di_Factory(self::TEST_CLASS);
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertSame($foo1, $foo2);

		$foo3 = $di->new_foo();
		$foo4 = $di->new_foo();
		$this->assertInstanceOf(self::TEST_CLASS, $foo3);
		$this->assertInstanceOf(self::TEST_CLASS, $foo4);
		$this->assertNotSame($foo3, $foo4);
		$this->assertNotSame($foo1, $foo3);
	}

	public function testContainerKeyNamespace() {
		$di = new Elgg_Di_Container();
		$di->foo = new Elgg_Di_Factory(self::TEST_CLASS);
		$di->new_foo = 'Foo';

		$this->assertInstanceOf(self::TEST_CLASS, $di->new_foo());
		$this->assertEquals('Foo', $di->new_foo);
	}

	public function testContainerRemove() {
		$di = new Elgg_Di_Container();
		$di->foo = 'Foo';

		unset($di->foo);
		$this->assertFalse(isset($di->foo));
	}

	public function testContainerAccessRemovedValue() {
		$di = new Elgg_Di_Container();
		$di->foo = 'Foo';
		unset($di->foo);

		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$di->foo;
	}

	public function testContainerRef() {
		$di1 = new Elgg_Di_Container();
		$di1->foo = 'Foo1';

		$di2 = new Elgg_Di_Container();
		$di2->foo = 'Foo2';

		$unboundFooRef = $di1->ref('foo');
		$boundFooRef = $di1->ref('foo', true);

		$this->assertInstanceOf('Elgg_Di_Reference', $unboundFooRef);
		$this->assertInstanceOf('Elgg_Di_Reference', $boundFooRef);

		$this->assertEquals('Foo2', $unboundFooRef->resolveValue($di2));
		$this->assertEquals('Foo1', $boundFooRef->resolveValue($di2));
	}
}

class ElggDiFactoryTestObject {
	public $calls;
	public $args;
	public function __construct() { $this->args = func_get_args(); }
	public function __call($name, $args) { $this->calls[$name] = $args[0]; }
}
