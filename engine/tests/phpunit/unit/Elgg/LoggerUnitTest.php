<?php

namespace Elgg;

use Psr\Log\LogLevel;

/**
 * @group UnitTests
 * @group Logger
 */
class LoggerUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var Logger
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->logger;
	}
	
	public function testLoggingOff() {
		$logger = $this->service;
		$logger->setLevel(false);
		$logger->log(LogLevel::NOTICE, "hello");
	}

	public function testLoggingLevelTooLow() {
		$logger = $this->service;
		$logger->setLevel(LogLevel::WARNING);
		$logger->log(LogLevel::NOTICE, "hello");
	}

	public function testLoggingLevelNotExist() {
		$logger = $this->service;
		$logger->log(12, "hello");
	}

	public function testDisablePreventsProcessingAndCapturesLogCalls() {
		$logger = $this->service;
		
		$logger->disable();
		
		$logger->error("Testing");
		
		$this->assertEquals([
			['message' => 'Testing', 'level' => LogLevel::ERROR],
		], $logger->enable());
	}

	public function testDisableEnableActsAsAStack() {
		$logger = $this->service;

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
	}
}
