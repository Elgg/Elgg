<?php

/**
 * @group UnitTests
 * @group Crypto
 */
class ElggCryptoUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $stub;

	public function up() {
		$this->stub = $this->getMockBuilder('\ElggCrypto')
			->setMethods(array('getRandomBytes'))
			->getMock();

		$this->stub->expects($this->any())
			->method('getRandomBytes')
			->will($this->returnCallback(array($this, 'mock_getRandomBytes')));
	}

	public function down() {

	}

	protected function getCrypto() {
		return new \ElggCrypto(_elgg_services()->siteSecret);
	}

	protected function getHmac() {
		return new \Elgg\Security\HmacFactory(_elgg_services()->siteSecret, $this->getCrypto());
	}

	function mock_getRandomBytes($length) {
		mt_srand(1);
		$bytes = '';
		for ($i = 0; $i < $length; $i++) {
			$bytes .= chr(mt_rand(0, 254));
		}
		return $bytes;
	}

	function testGeneratesMacInBase64Url() {
		$key = 'a very bad key';
		$data = '1';
		$expected = 'nL0lgXrVWgGK0Cmr9_PjqQcR2_PzuAHH114AsPZk-AM';
		$algo = 'sha256';

		$this->assertEquals($expected, $this->getHmac()->getHmac($data, $algo, $key)->getToken());
	}

	function testStringCastAffectsMacs() {
		$key = 'a very bad key';

		$t1 = $this->getHmac()->getHmac(1234, 'sha256', $key)->getToken();
		$t2 = $this->getHmac()->getHmac('1234', 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testMacAlteredByVaryingData() {
		$key = 'a very bad key';

		$t1 = $this->getHmac()->getHmac('1234', 'sha256', $key)->getToken();
		$t2 = $this->getHmac()->getHmac('1235', 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testMacAlteredByVaryingKey() {
		$key1 = 'a very bad key';
		$key2 = 'b very bad key';

		$t1 = $this->getHmac()->getHmac('1234', 'sha256', $key1)->getToken();
		$t2 = $this->getHmac()->getHmac('1234', 'sha256', $key2)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testCanAcceptDataAsArray() {
		$key = 'a very bad key';

		$token = $this->getHmac()->getHmac([12, 34], 'sha256', $key)->getToken();
		$matches = $this->getHmac()->getHmac([12, 34], 'sha256', $key)->matchesToken($token);

		$this->assertTrue($matches);
	}

	function testMacAlteredByArrayModification() {
		$key = 'a very bad key';

		$t1 = $this->getHmac()->getHmac([12, 34], 'sha256', $key)->getToken();
		$t2 = $this->getHmac()->getHmac([123, 4], 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

	function testMacAlteredByArrayTypeModification() {
		$key = 'a very bad key';

		$t1 = $this->getHmac()->getHmac([12, 34], 'sha256', $key)->getToken();
		$t2 = $this->getHmac()->getHmac([12, '34'], 'sha256', $key)->getToken();

		$this->assertNotEquals($t1, $t2);
	}

}
