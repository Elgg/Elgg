<?php
namespace Elgg;

require_once __DIR__ . '/../test_files/handlers/functions.php';
require_once __DIR__ . '/../test_files/handlers/class.php';

class HandlersServiceTest extends TestCase {

	/**
	 * @var HandlersService
	 */
	public $handlers;

	/**
	 * @var Logger
	 */
	public $logger;

	public function setUp() {
		$this->logger = _elgg_services()->logger;
		$this->logger->disable();
		$this->handlers = _elgg_services()->handlers;
	}

	public function tearDown() {
		$this->logger->enable();
	}

	public function testCanTypeHintFunctions() {
		$this->assertSame(null, $this->handlers->getParamTypeForCallable('test_null_1457227310'));
		$this->assertSame('', $this->handlers->getParamTypeForCallable('test_none_1457227310'));
		$this->assertSame('array', $this->handlers->getParamTypeForCallable('test_array_1457227310'));
		$this->assertSame('callable', $this->handlers->getParamTypeForCallable('test_callable_1457227310'));
		$this->assertSame('Elgg\\Hook', $this->handlers->getParamTypeForCallable('test_hook_1457227310'));
	}

	public function testCanTypeHintStaticMethods() {
		$class = test_1457227310::class;

		$this->assertSame(null, $this->handlers->getParamTypeForCallable([$class, 'test_null']));
		$this->assertSame('', $this->handlers->getParamTypeForCallable([$class, 'test_none']));
		$this->assertSame('array', $this->handlers->getParamTypeForCallable([$class, 'test_array']));
		$this->assertSame('callable', $this->handlers->getParamTypeForCallable([$class, 'test_callable']));
		$this->assertSame('Elgg\\Hook', $this->handlers->getParamTypeForCallable([$class, 'test_hook']));
	}

	public function testCanTypeHintDynamicMethods() {
		$class = new test_1457227310();

		$this->assertSame(null, $this->handlers->getParamTypeForCallable([$class, 'test_null']));
		$this->assertSame('', $this->handlers->getParamTypeForCallable([$class, 'test_none']));
		$this->assertSame('array', $this->handlers->getParamTypeForCallable([$class, 'test_array']));
		$this->assertSame('callable', $this->handlers->getParamTypeForCallable([$class, 'test_callable']));
		$this->assertSame('Elgg\\Hook', $this->handlers->getParamTypeForCallable([$class, 'test_hook']));
	}

	public function testCanTypeHintClosures() {
		$this->assertSame(null, $this->handlers->getParamTypeForCallable(function(){}));
		$this->assertSame('', $this->handlers->getParamTypeForCallable(function($arg){}));
		$this->assertSame('array', $this->handlers->getParamTypeForCallable(function(array $arg){}));
		$this->assertSame('callable', $this->handlers->getParamTypeForCallable(function(callable $arg){}));
		$this->assertSame('Elgg\\Hook', $this->handlers->getParamTypeForCallable(function(\Elgg\Hook $arg){}));
	}
}
