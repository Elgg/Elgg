<?php

namespace Elgg\Security;

class CryptoUnitTest extends \Elgg\UnitTestCase {

	protected function getCrypto() {
		return new Crypto();
	}

	protected function getHmac() {
		return new HmacFactory(_elgg_services()->siteSecret, $this->getCrypto());
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
