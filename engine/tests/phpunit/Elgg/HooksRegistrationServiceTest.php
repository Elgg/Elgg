<?php
namespace Elgg;

class HooksRegistrationServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var HooksRegistrationService
	 */
	public $mock;

	public function setUp() {
		parent::setUp();

		$this->mock = $this->getMockForAbstractClass('\Elgg\HooksRegistrationService');
	}
	
	public function testCanRegisterHandlers() {
		$f = function () {};

		$this->assertTrue($this->mock->registerHandler('foo', 'bar', 'callback1'));
		$this->assertTrue($this->mock->registerHandler('foo', 'bar', $f));
		$this->assertTrue($this->mock->registerHandler('foo', 'baz', 'callback3', 100));

		$expected = array(
			'foo' => array(
				'bar' => array(
					500 => 'callback1',
					501 => $f,
				),
				'baz' => array(
					100 => 'callback3'
				)
			)
		);

		$this->assertSame($expected, $this->mock->getAllHandlers());

		// check possibly invalid callbacks
		$this->assertFalse($this->mock->registerHandler('foo', 'bar', 1234));
	}
	
	public function testCanUnregisterHandlers() {
		$o = new HooksRegistrationServiceTest_invokable();

		$this->mock->registerHandler('foo', 'bar', 'callback1');
		$this->mock->registerHandler('foo', 'bar', 'callback2', 100);
		$this->mock->registerHandler('foo', 'bar', 'callback2', 150);
		$this->mock->registerHandler('foo', 'bar', [$o, '__invoke'], 300);
		$this->mock->registerHandler('foo', 'bar', [$o, '__invoke'], 300);
		$this->mock->registerHandler('foo', 'bar', [$o, '__invoke'], 300);

		$this->assertTrue($this->mock->unregisterHandler(
			'foo', 'bar', 'callback2'));
		$this->assertTrue($this->mock->unregisterHandler(
			'foo', 'bar', HooksRegistrationServiceTest_invokable::KLASS . '::__invoke'));
		$this->assertTrue($this->mock->unregisterHandler(
			'foo', 'bar', [HooksRegistrationServiceTest_invokable::KLASS, '__invoke']));
		$this->assertTrue($this->mock->unregisterHandler(
			'foo', 'bar', [$o, '__invoke']));

		$expected = array(
			'foo' => array(
				'bar' => array(
					// only one removed
					150 => 'callback2',

					500 => 'callback1',
				)
			)
		);
		$this->assertSame($expected, $this->mock->getAllHandlers());

		// check unregistering things that aren't registered
		$this->assertFalse($this->mock->unregisterHandler('foo', 'bar', 'not_valid'));
	}

	public function testOnlyOneHandlerUnregistered() {
		$this->mock->registerHandler('foo', 'bar', 'callback');
		$this->mock->registerHandler('foo', 'bar', 'callback');

		$this->assertTrue($this->mock->unregisterHandler('foo', 'bar', 'callback'));

		$this->assertSame([501 => 'callback'], $this->mock->getAllHandlers()['foo']['bar']);
	}

	public function testGetOrderedHandlers() {
		$this->mock->registerHandler('foo', 'bar', 'callback1');
		$this->mock->registerHandler('foo', 'bar', 'callback2');
		$this->mock->registerHandler('foo', 'baz', 'callback3', 100);

		$expected_foo_bar = array(
			'callback1',
			'callback2'
		);

		$expected_foo_baz = array(
			'callback3'
		);

		$this->assertSame($expected_foo_bar, $this->mock->getOrderedHandlers('foo', 'bar'));
		$this->assertSame($expected_foo_baz, $this->mock->getOrderedHandlers('foo', 'baz'));
	}
}

class HooksRegistrationServiceTest_invokable {
	const KLASS = __CLASS__;
	function __invoke() {}
}
