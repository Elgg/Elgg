<?php

namespace Elgg;

class LoggerTest extends \Elgg\TestCase {

	public function testLoggingOff() {
		$logger = $this->getLoggerInstance();
		$logger->setLevel(\Elgg\Logger::OFF);
		$this->assertFalse($logger->log("hello"));
	}

	public function testLoggingLevelTooLow() {
		$logger = $this->getLoggerInstance();
		$logger->setLevel(\Elgg\Logger::WARNING);
		$this->assertFalse($logger->log("hello", \Elgg\Logger::NOTICE));
	}

	public function testLoggingLevelNotExist() {
		$logger = $this->getLoggerInstance();
		$this->assertFalse($logger->log("hello", 123));
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
			['message' => 'Testing', 'level' => Logger::ERROR],
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
		$logger->warn("Test2");

		$this->assertEquals([
			['message' => 'Test2', 'level' => Logger::WARNING],
				], $logger->enable());

		$this->assertEquals([
			['message' => 'Test1', 'level' => Logger::ERROR],
				], $logger->enable());

		$this->assertEquals(0, $num_processed);

		$logger->error("Test3");
		$this->assertEquals(1, $num_processed, "Last enable() did not enable processing");

		$hooks->restore();
	}

	protected function getLoggerInstance() {
		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$sp = _elgg_services();
		return new \Elgg\Logger($mock, $sp->config, $sp->context);
	}

}
