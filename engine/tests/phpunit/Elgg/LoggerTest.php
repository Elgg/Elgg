<?php

class Elgg_LoggerTest extends PHPUnit_Framework_TestCase {

	public function testLoggingOff() {
		$mock = $this->getMock('Elgg_PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$logger = new Elgg_Logger($mock);
		$logger->setLevel(Elgg_Logger::OFF);
		$this->assertFalse($logger->log("hello"));
	}

	public function testLoggingLevelTooLow() {
		$mock = $this->getMock('Elgg_PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$logger = new Elgg_Logger($mock);
		$logger->setLevel(Elgg_Logger::WARNING);
		$this->assertFalse($logger->log("hello", Elgg_Logger::NOTICE));
	}

	public function testLoggingLevelNotExist() {
		$mock = $this->getMock('Elgg_PluginHooksService', array('trigger'));
		$mock->expects($this->never())->method('trigger');
		$logger = new Elgg_Logger($mock);
		$this->assertFalse($logger->log("hello", 123));
	}
}
