<?php
namespace Elgg;


class LoggerTest extends \PHPUnit_Framework_TestCase {

	public function testLoggingOff() {
		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$logger = new \Elgg\Logger($mock);
		$logger->setLevel(\Elgg\Logger::OFF);
		$this->assertFalse($logger->log("hello"));
	}

	public function testLoggingLevelTooLow() {
		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$logger = new \Elgg\Logger($mock);
		$logger->setLevel(\Elgg\Logger::WARNING);
		$this->assertFalse($logger->log("hello", \Elgg\Logger::NOTICE));
	}

	public function testLoggingLevelNotExist() {
		$mock = $this->getMock('\Elgg\PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$logger = new \Elgg\Logger($mock);
		$this->assertFalse($logger->log("hello", 123));
	}
}

