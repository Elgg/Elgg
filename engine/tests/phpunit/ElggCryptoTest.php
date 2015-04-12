<?php

class ElggCryptoTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $stub;

	/**
	 * @see ElggCrypto
	 * @see ElggCrypto::getRandomBytes
	 */
	protected function setUp() {
		$this->stub = $this->getMockBuilder('\ElggCrypto')
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
			array(32, \ElggCrypto::CHARS_HEX, '9301b7edfdddb3fedac2e89a2f9da655'),
			array(32, \ElggCrypto::CHARS_PASSWORD, 'kl4lmjwyrpyh6rpqct3rkd9zvxwvqww8'),
			array(32, "0123456789", "78181215379307389761767024720714"),
		);
	}

	/**
	 * @dataProvider provider
	 */
	function testGetRandomString($length, $chars, $expected) {
		$this->assertSame($expected, $this->stub->getRandomString($length, $chars));
	}

	function testGeneratesMacInBase64Url() {
		$crypto = new ElggCrypto();
		$key = 'a very bad key';
		$data = '1';
		$expected = 'nL0lgXrVWgGK0Cmr9_PjqQcR2_PzuAHH114AsPZk-AM';
		$algo = 'sha256';

		$this->assertEquals($expected, $crypto->getHmac($data, $algo, $key)->getToken());
	}

	function testStringCastAffectsMacs() {
		$crypto = new ElggCrypto();
		$key = 'a very bad key';

		$t1 = $crypto->getHmac(1234, 'sha256', $key)->getToken();
		$t2 = $crypto->getHmac('1234', 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testMacAlteredByVaryingData() {
		$crypto = new ElggCrypto();
		$key = 'a very bad key';

		$t1 = $crypto->getHmac('1234', 'sha256', $key)->getToken();
		$t2 = $crypto->getHmac('1235', 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testMacAlteredByVaryingKey() {
		$crypto = new ElggCrypto();
		$key1 = 'a very bad key';
		$key2 = 'b very bad key';

		$t1 = $crypto->getHmac('1234', 'sha256', $key1)->getToken();
		$t2 = $crypto->getHmac('1234', 'sha256', $key2)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testCanAcceptDataAsArray() {
		$crypto = new ElggCrypto();
		$key = 'a very bad key';

		$token = $crypto->getHmac([12, 34], 'sha256', $key)->getToken();
		$matches = $crypto->getHmac([12, 34], 'sha256', $key)->matchesToken($token);

		$this->assertTrue($matches);
	}

	function testMacAlteredByArrayModification() {
		$crypto = new ElggCrypto();
		$key = 'a very bad key';

		$t1 = $crypto->getHmac([12, 34], 'sha256', $key)->getToken();
		$t2 = $crypto->getHmac([123, 4], 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testMacAlteredByArrayTypeModification() {
		$crypto = new ElggCrypto();
		$key = 'a very bad key';

		$t1 = $crypto->getHmac([12, 34], 'sha256', $key)->getToken();
		$t2 = $crypto->getHmac([12, '34'], 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}
}
