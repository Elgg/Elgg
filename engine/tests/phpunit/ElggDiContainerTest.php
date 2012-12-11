<?php

class ElggDiContainerTest extends PHPUnit_Framework_TestCase {

	const TEST_CLASS = 'ElggDiFactoryTestObject';

	protected function getContainerMock(array $props = array()) {
		$di = $this->getMock('Elgg_Di_Container');
		foreach ($props as $name => $val) {
			$di->{$name} = $val;
		}
		return $di;
	}

	public function getFoo(Elgg_Di_Container $di) {
		return $di->foo;
	}

	public function testInvokerPassesContainerToCallable() {
		$di = $this->getContainerMock(array('foo' => 'Foo'));
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
		$obj->resolveValue($this->getContainerMock());
	}

	public function testReference() {
		$di1 = $this->getContainerMock(array('foo' => 'Foo1'));
		$di2 = $this->getContainerMock(array('foo' => 'Foo2'));

		$obj = new Elgg_Di_Reference('foo');
		$this->assertEquals('Foo1', $obj->resolveValue($di1));
		$this->assertEquals('Foo2', $obj->resolveValue($di2));

		$obj = new Elgg_Di_Reference('foo', $di1);
		$this->assertEquals('Foo1', $obj->resolveValue($di1));
		$this->assertEquals('Foo1', $obj->resolveValue($di2));
	}

	public function testFactoryClassAndArguments() {
		$di = $this->getContainerMock();

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$obj = $fact->resolveValue($di);
		$this->assertInstanceOf(self::TEST_CLASS, $obj);
		$this->assertEmpty($obj->args);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS, array('one', 2));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('one', 2), $obj->args);
	}

	public function testFactoryCallsSetters() {
		$di = $this->getContainerMock(array('bar' => 'Bar'));

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$fact->setSetter('setArray', array(1, 2, 3));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('setArray' => array(1, 2, 3)), $obj->calls);
	}

	public function testFactoryCanUseResolvedValues() {
		$di = $this->getContainerMock(array(
			'foo' => 'Foo',
			'bar' => 'Bar',
			'testObjClass' => self::TEST_CLASS,
			'anArray' => array(1, 2, 3),
		));

		$fact = new Elgg_Di_Factory(new Elgg_Di_Reference('testObjClass'));
		$obj = $fact->resolveValue($di);
		$this->assertInstanceOf(self::TEST_CLASS, $obj);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS, array(new Elgg_Di_Reference('foo')));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('Foo'), $obj->args);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$fact->setSetter('setBar', new Elgg_Di_Reference('bar'));
		$obj = $fact->resolveValue($di);
		$this->assertEquals(array('setBar' => 'Bar'), $obj->calls);
	}

	public function testFactoryRequiresClassNameToResolveToString() {
		$di = $this->getContainerMock(array('anArray' => array(1, 2, 3)));
		$fact = new Elgg_Di_Factory(new Elgg_Di_Reference('anArray'));

		$this->setExpectedException('ErrorException');
		$fact->resolveValue($di);
	}

	public function testContainerEmpty() {
		$di = new Elgg_Di_Container();
		$this->assertFalse($di->has('foo'));
	}

	public function testContainerSetNonResolvable() {
		$di = new Elgg_Di_Container();

		$this->assertSame($di, $di->set('foo', 'Foo'));
		$this->assertTrue($di->has('foo'));
		$this->assertTrue($di->isShared('foo'), 'Non resolvables are shared by default');

	}

	public function testContainerSetAfterReadAndShared() {
		$di = new Elgg_Di_Container();

		$di->set('foo', 'Foo');
		$di->set('foo', 'Foo2');
		$this->assertEquals('Foo2', $di->foo);

		$di->set('bar', new Elgg_Di_Factory(self::TEST_CLASS), true);
		$di->bar;
		$di->set('bar', 'Bar');
		$this->assertEquals('Bar', $di->bar);
	}

	public function testContainerSetResolvable() {
		$di = new Elgg_Di_Container();

		$di->set('foo', new Elgg_Di_Factory(self::TEST_CLASS));
		$this->assertTrue($di->has('foo'));
		$this->assertFalse($di->isShared('foo'), 'Non resolvables not shared by default');
	}

	public function testContainerGetNonShared() {
		$di = new Elgg_Di_Container();

		$di->set('foo', new Elgg_Di_Factory(self::TEST_CLASS));
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertNotSame($foo1, $foo2);
	}

	public function testContainerGetShared() {
		$di = new Elgg_Di_Container();

		$di->set('foo', new Elgg_Di_Factory(self::TEST_CLASS), true);
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertSame($foo1, $foo2);

		$di->set('foo', new stdClass()); // non-resolvable shared by default
		$foo1 = $di->foo;
		$foo2 = $di->foo;
		$this->assertInstanceOf('stdClass', $foo1);
		$this->assertSame($foo1, $foo2);
	}

	public function testContainerRemove() {
		$di = new Elgg_Di_Container();
		$di->set('foo', 'Foo');

		$this->assertSame($di, $di->remove('foo'));
		$this->assertFalse($di->has('foo'));
	}

	public function testContainerAccessRemovedValue() {
		$di = new Elgg_Di_Container();
		$di->set('foo', 'Foo');
		$di->remove('foo');

		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$di->foo;
	}

	public function testContainerRef() {
		$di1 = new Elgg_Di_Container();
		$di1->set('foo', 'Foo1');

		$di2 = new Elgg_Di_Container();
		$di2->set('foo', 'Foo2');

		$unboundFooRef = $di1->ref('foo');
		$boundFooRef = $di1->ref('foo', true);

		$this->assertInstanceOf('Elgg_Di_Reference', $unboundFooRef);
		$this->assertInstanceOf('Elgg_Di_Reference', $boundFooRef);

		$this->assertEquals('Foo2', $unboundFooRef->resolveValue($di2));
		$this->assertEquals('Foo1', $boundFooRef->resolveValue($di2));
	}

	public function testContainerSetService() {
		$di = new Elgg_Di_Container();
		$this->assertSame($di, $di->setService('gaz', self::TEST_CLASS));
		$this->assertTrue($di->isShared('gaz'));
		$this->assertInstanceOf(self::TEST_CLASS, $di->gaz);
	}

	public function testContainerAccessMissingValue() {
		$di = new Elgg_Di_Container();
		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$di->foo;
	}
}

class ElggDiFactoryTestObject {
	public $calls;
	public $args;
	public function __construct() { $this->args = func_get_args(); }
	public function __call($name, $args) { $this->calls[$name] = $args[0]; }
}
