<?php

class ElggDiContainerTest extends PHPUnit_Framework_TestCase {

	const TEST_CLASS = 'ElggDiFactoryTestObject';

	protected function getTestContainer(array $props = array()) {
		$di = new Elgg_Di_Container();
		foreach ($props as $name => $val) {
			if ($val instanceof Elgg_Di_FactoryInterface) {
				$di->setFactory($name, $val);
			} else {
				$di->setValue($name, $val);
			}
		}
		return $di;
	}

	public function getFoo(Elgg_Di_Container $di) {
		return $di->get('foo');
	}

	public function testInvoker() {
		$di = $this->getTestContainer(array('foo' => 'Foo'));
		$obj = new Elgg_Di_Invoker(array($this, 'getFoo'), array($di));

		$foo = $obj->createValue($di);
		$this->assertEquals('Foo', $foo);
	}

	public function testInvokerChecksCallableInConstructor() {
		$this->setExpectedException('InvalidArgumentException');
		new Elgg_Di_Invoker(false);
	}

	public function testInvokerChecksCallableAtReadTime() {
		$obj = new Elgg_Di_Invoker('nonexistent_function');
		$this->setExpectedException('ErrorException');
		$obj->createValue($this->getTestContainer());
	}

	public function testReference() {
		$di1 = $this->getTestContainer(array(
			'foo' => 'Foo1',
		));

		$ref1 = new Elgg_Di_Reference('foo');
		$this->assertEquals('Foo1', $ref1->createValue($di1));
	}

	public function testFactoryClassAndArguments() {
		$di = $this->getTestContainer();

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$obj = $fact->createValue($di);
		$this->assertInstanceOf(self::TEST_CLASS, $obj);
		$this->assertEmpty($obj->args);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS, array('one', 2));
		$obj = $fact->createValue($di);
		$this->assertEquals(array('one', 2), $obj->args);
	}

	public function testFactoryCallsSetters() {
		$di = $this->getTestContainer(array('bar' => 'Bar'));

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$fact->addMethodCall('setArray', array(1, 2, 3));
		$obj = $fact->createValue($di);
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
		$obj = $fact->createValue($di);
		$this->assertInstanceOf(self::TEST_CLASS, $obj);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS, array($di->ref('foo')));
		$obj = $fact->createValue($di);
		$this->assertEquals(array('Foo'), $obj->args);

		$fact = new Elgg_Di_Factory(self::TEST_CLASS);
		$fact->addMethodCall('setBar', $di->ref('bar'));
		$obj = $fact->createValue($di);
		$this->assertEquals(array('setBar' => 'Bar'), $obj->calls);
	}

	public function testFactoryRequiresClassNameToResolveToString() {
		$di = $this->getTestContainer(array('anArray' => array(1, 2, 3)));
		$fact = new Elgg_Di_Factory($di->ref('anArray'));

		$this->setExpectedException('ErrorException');
		$fact->createValue($di);
	}

	public function testContainerEmpty() {
		$di = new Elgg_Di_Container();
		$this->assertFalse($di->has('foo'));
	}

	public function testContainerSetValue() {
		$di = new Elgg_Di_Container();

		$this->assertSame($di, $di->setValue('foo', 'Foo'));
		$this->assertTrue($di->has('foo'));
		$this->assertEquals('Foo', $di->get('foo'));

		$this->setExpectedException('InvalidArgumentException');
		$di->setValue('foo', new Elgg_Di_Factory(self::TEST_CLASS));
	}

	public function testContainerSetFactory() {
		$di = new Elgg_Di_Container();

		$this->assertSame($di, $di->setFactory('foo', new Elgg_Di_Factory(self::TEST_CLASS)));
		$this->assertTrue($di->has('foo'));
		$this->assertInstanceOf(self::TEST_CLASS, $di->get('foo'));

		$this->setExpectedException('PHPUnit_Framework_Error');
		$di->setFactory('foo', 'Foo');
	}

	public function testContainerGetMissingValue() {
		$di = new Elgg_Di_Container();
		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$di->get('foo');
	}

	public function testContainerGetFactoryValues() {
		$di = new Elgg_Di_Container();

		$di->setFactory('foo', new Elgg_Di_Factory(self::TEST_CLASS));
		$foo1 = $di->get('foo');
		$foo2 = $di->get('foo');
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertNotSame($foo1, $foo2);

		$di->setFactory('foo', new Elgg_Di_Factory(self::TEST_CLASS), true);
		$foo1 = $di->get('foo');
		$foo2 = $di->get('foo');
		$this->assertInstanceOf(self::TEST_CLASS, $foo1);
		$this->assertSame($foo1, $foo2);
	}

	public function testContainerRemove() {
		$di = new Elgg_Di_Container();
		$di->setValue('foo', 'Foo');

		$di->remove('foo');
		$this->assertFalse($di->has('foo'));
	}

	public function testContainerAccessRemovedValue() {
		$di = new Elgg_Di_Container();
		$di->setValue('foo', 'Foo');
		$di->remove('foo');

		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$di->get('foo');
	}

	public function testContainerRef() {
		$di1 = new Elgg_Di_Container();
		$di1->setValue('foo', 'Foo1');
		$di2 = new Elgg_Di_Container();
		$di2->setValue('foo', 'Foo2');

		$ref = $di1->ref('foo');
		$this->assertInstanceOf('Elgg_Di_Reference', $ref);

		$this->assertEquals('Foo2', $ref->createValue($di2));
		$this->assertEquals('Foo1', $ref->createValue($di1));
	}
}

class ElggDiFactoryTestObject {
	public $calls;
	public $args;
	public function __construct() { $this->args = func_get_args(); }
	public function __call($name, $args) { $this->calls[$name] = $args[0]; }
}
