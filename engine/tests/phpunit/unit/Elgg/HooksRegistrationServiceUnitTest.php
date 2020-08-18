<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class HooksRegistrationServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var HooksRegistrationService
	 */
	public $mock;

	public function up() {
		$this->mock = $this->getMockForAbstractClass('\Elgg\HooksRegistrationService');
	}

	public function down() {

	}

	public function testCanRegisterHandlers() {
		$f = function () {

		};

		$this->assertTrue($this->mock->registerHandler('foo', 'bar', 'callback1'));
		$this->assertTrue($this->mock->registerHandler('foo', 'bar', $f));
		$this->assertTrue($this->mock->registerHandler('foo', 'baz', 'callback3', 100));

		$expected = [
			'foo' => [
				'bar' => [
					500 => ['callback1', $f],
				],
				'baz' => [
					100 => ['callback3'],
				],
			],
		];

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

		$expected = [
			'foo' => [
				'bar' => [
					500 => ['callback1'],
					// only one removed
					150 => ['callback2'],
				]
			]
		];
		$this->assertSame($expected, $this->mock->getAllHandlers());

		// check unregistering things that aren't registered
		$this->assertFalse($this->mock->unregisterHandler('foo', 'bar', 'not_valid'));
	}

	public function testCanClearMultipleHandlersAtOnce() {
		$o = new HooksRegistrationServiceTest_invokable();

		$this->mock->registerHandler('foo', 'bar', 'callback1');
		$this->mock->registerHandler('foo', 'baz', 'callback1', 10);
		$this->mock->registerHandler('foo', 'bar', 'callback2', 100);
		$this->mock->registerHandler('foo', 'bar', 'callback2', 150);

		$expected = [
			'foo' => [
				'baz' => [
					10 => ['callback1'],
				]
			]
		];
		// clearHandlers should remove everything registrered for 'foo', 'bar', but not 'foo', 'baz'
		$this->mock->clearHandlers('foo', 'bar');

		$this->assertSame($expected, $this->mock->getAllHandlers());
	}

	public function testOnlyOneHandlerUnregistered() {
		$this->mock->registerHandler('foo', 'bar', 'callback');
		$this->mock->registerHandler('foo', 'bar', 'callback');

		$this->assertTrue($this->mock->unregisterHandler('foo', 'bar', 'callback'));

		$this->assertSame([500 => ['callback']], $this->mock->getAllHandlers()['foo']['bar']);
	}

	public function testGetOrderedHandlers() {
		$this->mock->registerHandler('foo', 'bar', 'callback1');
		$this->mock->registerHandler('foo', 'bar', 'callback2');
		$this->mock->registerHandler('all', 'all', 'callback4', 100);
		$this->mock->registerHandler('foo', 'baz', 'callback3', 100);

		$expected_foo_bar = [
			'callback4', // first even though it's [all, all]
			'callback1',
			'callback2',
		];

		$expected_foo_baz = [
			'callback4', // first even though it's [all, all]
			'callback3',
		];

		$this->assertSame($expected_foo_bar, $this->mock->getOrderedHandlers('foo', 'bar'));
		$this->assertSame($expected_foo_baz, $this->mock->getOrderedHandlers('foo', 'baz'));
	}

	public function testCanBackupAndRestoreRegistrations() {
		$this->mock->registerHandler('foo', 'bar', 'callback2');
		$this->mock->registerHandler('all', 'all', 'callback4', 100);
		$handlers = $this->mock->getAllHandlers();

		$this->mock->backup();
		$this->assertEmpty($this->mock->getAllHandlers());

		$this->mock->restore();
		$this->assertEquals($handlers, $this->mock->getAllHandlers());
	}

	public function testBackupIsAStack() {
		$this->mock->registerHandler('foo', 'bar', 'callback2');
		$handlers1 = $this->mock->getAllHandlers();
		$this->mock->backup();

		$this->mock->registerHandler('all', 'all', 'callback4', 100);
		$handlers2 = $this->mock->getAllHandlers();
		$this->mock->backup();

		$this->mock->restore();
		$this->assertEquals($handlers2, $this->mock->getAllHandlers());

		$this->mock->restore();
		$this->assertEquals($handlers1, $this->mock->getAllHandlers());
	}

}

class HooksRegistrationServiceTest_invokable {

	const KLASS = __CLASS__;

	function __invoke() {

	}

}
