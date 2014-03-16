<?php

class ElggCryptoTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $stub;

	protected function setUp() {
		$this->stub = $this->getMockBuilder('ElggCrypto')
			->setMethods(array('getRandomBytes'))
			->getMock();

		$this->stub->expects($this->any())
			->method('getRandomBytes')
			->will($this->returnCallback(array($this, 'mock_getRandomBytes')));
	}

	function mock_getRandomBytes($length) {
		mt_srand(1);
		$bytes = '';
		for ($i = 0; $i < $length; $i++) {
			$bytes .= chr(mt_rand(0, 254));
		}
		return $bytes;
	}

	function provider() {
		return array(
			array(32, null, 'kwG37f3ds_7awuiaL52mVWXud9dqT1GF'),
			array(32, ElggCrypto::CHARS_HEX, '9301b7edfdddb3fedac2e89a2f9da655'),
			array(32, ElggCrypto::CHARS_PASSWORD, 'kl4lmjwyrpyh6rpqct3rkd9zvxwvqww8'),
			array(32, "0123456789", "78181215379307389761767024720714"),
		);
	}

	/**
	 * @dataProvider provider
	 */
	function testGetRandomString($length, $chars, $expected) {
		$this->assertSame($expected, $this->stub->getRandomString($length, $chars));
	}
}
