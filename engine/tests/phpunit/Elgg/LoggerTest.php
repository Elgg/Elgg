<?php
namespace Elgg;


class LoggerTest extends \PHPUnit_Framework_TestCase {

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

	protected function getLoggerInstance() {
		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$sp = _elgg_services();
		return new \Elgg\Logger($mock, $sp->config, $sp->context);
	}
}

