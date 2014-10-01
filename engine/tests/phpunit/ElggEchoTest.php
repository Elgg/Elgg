<?php

class ElggEchoTest extends \PHPUnit_Framework_TestCase {

	protected $key1 = '__echo_test_key1';
	protected $key2 = '__echo_test_key2';
	protected $realLog;

	public function setUp() {
		$this->realLog = _elgg_services()->logger;
		_elgg_services()->setValue('logger', $this->getMock('Elgg\\Logger', array(), array(), '', false));

		add_translation('xx', array(
			$this->key1 => 'xx1 %s',
		));
		add_translation('en', array(
			$this->key1 => 'en1 %s',
			$this->key2 => 'en2 %s',
		));
	}

	public function tearDown() {
		_elgg_services()->setValue('logger', $this->realLog);
	}

	public function testCanTranslate() {
		$this->assertEquals('xx1 %s', elgg_echo($this->key1, array(), 'xx'));
		$this->assertEquals('xx1 z', elgg_echo($this->key1, array('z'), 'xx'));
	}

	public function testFallsBackToEnglish() {
		$mock = _elgg_services()->logger;
		/* @var PHPUnit_Framework_MockObject_MockObject $mock */
		$mock->expects($this->exactly(2))->method('log');

		$this->assertEquals('en2 %s', elgg_echo($this->key2, array(), 'xx'));
		$this->assertEquals('en2 z', elgg_echo($this->key2, array('z'), 'xx'));
	}

	public function testStringKeyFallsBackToKey() {
		$key = __METHOD__;

		$mock = _elgg_services()->logger;
		/* @var PHPUnit_Framework_MockObject_MockObject $mock */
		$mock->expects($this->once())->method('log');

		$this->assertEquals($key, elgg_echo($key));
	}

	public function testCanAcceptMultipleKeys() {
		$keys = array(__METHOD__, $this->key2);
		$this->assertEquals('en2 z', elgg_echo($keys, array('z'), 'xx'));
	}

	public function testArrayKeyFallsBackToFalse() {
		$key = __METHOD__;

		$mock = _elgg_services()->logger;
		/* @var PHPUnit_Framework_MockObject_MockObject $mock */
		$mock->expects($this->never())->method('log');

		$this->assertFalse(elgg_echo(array($key)));
	}
}