<?php

namespace Elgg;

use Psr\Log\LogLevel;

/**
 * @group UnitTests
 * @group Logger
 */
class LoggerUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testLoggingOff() {
		$logger = $this->getLoggerInstance();
		$logger->setLevel(false);
		$this->assertFalse($logger->log(LogLevel::NOTICE, "hello"));
	}

	public function testLoggingLevelTooLow() {
		$logger = $this->getLoggerInstance();
		$logger->setLevel(LogLevel::WARNING);
		$this->assertFalse($logger->log(LogLevel::NOTICE, "hello"));
	}

	public function testLoggingLevelNotExist() {
		$logger = $this->getLoggerInstance();
		$this->assertFalse($logger->log(12, "hello"));
	}

	public function testDisablePreventsProcessingAndCapturesLogCalls() {
		$logger = _elgg_services()->logger;
		$logger->disable();
		$hooks = _elgg_services()->hooks;
		$logger->setHooks($hooks);
		$hooks->backup();

		$num_processed = 0;
		$hooks->registerHandler('debug', 'log', function () use (&$num_processed) {
			$num_processed++;
			return false;
		});
		$logger->error("Testing");

		$this->assertEquals(0, $num_processed, "disable() still allowed log to be processed");

		$captured = $logger->enable();

		$this->assertEquals([
			['message' => 'Testing', 'level' => LogLevel::ERROR],
				], $captured);

		$hooks->restore();
	}

	public function testDisableEnableActsAsAStack() {
		$logger = _elgg_services()->logger;
		$hooks = _elgg_services()->hooks;
		$hooks->backup();

		$num_processed = 0;
		$hooks->registerHandler('debug', 'log', function () use (&$num_processed) {
			$num_processed++;
			return false;
		});

		$logger->disable();
		$logger->error("Test1");

		$logger->disable();
		$logger->warning("Test2");

		$this->assertEquals([
			['message' => 'Test2', 'level' => LogLevel::WARNING],
				], $logger->enable());

		$this->assertEquals([
			['message' => 'Test1', 'level' => LogLevel::ERROR],
				], $logger->enable());

		$this->assertEquals(0, $num_processed);

		$logger->error("Test3");
		$this->assertEquals(1, $num_processed, "Last enable() did not enable processing");

		$hooks->restore();
	}

	protected function getLoggerInstance() {
		$mock = $this->createMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');

		$logger =  new \Elgg\Logger('elgg');
		$logger->setHooks($mock);

		$sp = _elgg_services();
		$sp->setValue('logger', $logger);

		return $logger;
	}

}
