<?php

class ElggDiContainerTest extends PHPUnit_Framework_TestCase {

	protected function getContainerMock(array $props = array()) {
		$container = $this->getMock('Elgg_Di_Container');
		foreach ($props as $name => $val) {
			$container->{$name} = $val;
		}
		return $container;
	}

	public function getFoo(Elgg_Di_Container $container) {
		return $container->foo;
	}

	public function testInvoker() {
		$container = $this->getContainerMock(array('foo' => 'Foo'));

		$obj = new Elgg_Di_Invoker(array($this, 'getFoo'));

		$foo = $obj->resolveValue($container);
		$this->assertEquals('Foo', $foo);

		$this->setExpectedException('InvalidArgumentException');
		$obj = new Elgg_Di_Invoker(false);

		$obj = new Elgg_Di_Invoker('y7r8437843');
		$this->setExpectedException('ErrorException');
		$obj->resolveValue($container);
	}

	public function testReference() {
		$container = $this->getContainerMock(array('foo' => 'Foo'));

		$obj = new Elgg_Di_Reference('foo');

		$foo = $obj->resolveValue($container);
		$this->assertEquals('Foo', $foo);
	}

	public function testFactoryClassAndArguments() {
		$testClass = 'ElggDiFactoryTestObject';
		$container = $this->getContainerMock();

		$fact = new Elgg_Di_Factory($testClass);
		$obj = $fact->resolveValue($container);
		$this->assertInstanceOf($testClass, $obj);
		$this->assertEmpty($obj->args);

		$fact = new Elgg_Di_Factory($testClass, array('one', 2));
		$obj = $fact->resolveValue($container);
		$this->assertEquals(array('one', 2), $obj->args);
	}

	public function testFactoryCallsSetters() {
		$testClass = 'ElggDiFactoryTestObject';
		$container = $this->getContainerMock(array('bar' => 'Bar'));

		$fact = new Elgg_Di_Factory($testClass);
		$fact->setSetter('setArray', array(1, 2, 3));
		$obj = $fact->resolveValue($container);
		$this->assertEquals(array('setArray' => array(1, 2, 3)), $obj->calls);
	}

	public function testFactoryCanUseResolvedValues() {
		$testClass = 'ElggDiFactoryTestObject';
		$container = $this->getContainerMock(array(
			'foo' => 'Foo',
			'bar' => 'Bar',
			'testObjClass' => $testClass,
			'anArray' => array(1, 2, 3),
		));

		$fact = new Elgg_Di_Factory(new Elgg_Di_Reference('testObjClass'));
		$obj = $fact->resolveValue($container);
		$this->assertInstanceOf($testClass, $obj);

		$fact = new Elgg_Di_Factory(new Elgg_Di_Reference('anArray'));
		$this->setExpectedException('ErrorException');
		$obj = $fact->resolveValue($container);

		$fact = new Elgg_Di_Factory($testClass, array(new Elgg_Di_Reference('foo')));
		$obj = $fact->resolveValue($container);
		$this->assertEquals(array('Foo'), $obj->args);

		$fact = new Elgg_Di_Factory($testClass);
		$fact->setSetter('setBar', new Elgg_Di_Reference('bar'));
		$obj = $fact->resolveValue($container);
		$this->assertEquals(array('setBar' => 'Bar'), $obj->calls);
	}

	public function testContainer() {
		$cont = new Elgg_Di_Container();

		$this->assertFalse($cont->has('foo'));
		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$cont->foo;

		$cont->set('foo', 'Foo');
		$this->assertEquals('Foo', $cont->foo);
		$this->assertTrue($cont->has('foo'));
		$this->assertTrue($cont->isShared('foo'), 'Non resolvables are shared by default');

		$cont->set('foo', 'Bar');
		$this->assertEquals('Bar', $cont->foo);

		$cont->remove('foo');
		$this->assertFalse($cont->has('foo'));
		$this->setExpectedException('Elgg_Di_Exception_MissingValueException');
		$cont->foo;

		$cont->set('bar', new Elgg_Di_Factory('ElggDiFactoryTestObject'));
		$cont->set('foo', new Elgg_Di_Reference('bar'));
		$this->assertFalse($cont->isShared('foo'));

		$foo1 = $cont->foo;
		$foo2 = $cont->foo;
		$this->assertNotSame($foo1, $foo2);

		$cont->makeShared('foo');
		$foo1 = $cont->foo;
		$foo2 = $cont->foo;
		$this->assertSame($foo1, $foo2);
		$this->assertTrue($cont->isShared('foo'));

		$cont->set('bar', new Elgg_Di_Factory('ElggDiFactoryTestObject'), true);
		$this->assertTrue($cont->isShared('bar'));
		$obj1 = $cont->bar;
		$obj2 = $cont->bar;
		$this->assertSame($obj1, $obj2);

	}//*/
}

class ElggDiFactoryTestObject {
	public $calls;
	public $args;
	public function __construct() { $this->args = func_get_args(); }
	public function __call($name, $args) { $this->calls[$name] = $args[0]; }
}
