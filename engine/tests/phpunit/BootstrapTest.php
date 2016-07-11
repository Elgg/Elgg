<?php

class BootstrapTest extends \PHPUnit_Framework_TestCase {

	public function testCanRestoreServiceProvider() {
		$sp = _elgg_services();
		$old_site_secret = $sp->siteSecret;
		$old_crypto = $sp->crypto;

		$site_secret = new \Elgg\Database\SiteSecret(_elgg_services()->datalist);
		$site_secret->setTestingSecret('z0000000000000000000000000000000');
		_elgg_services()->setValue('siteSecret', $site_secret);
		_elgg_services()->setValue('crypto', new ElggCrypto($site_secret));

		$this->assertNotSame(_elgg_services()->siteSecret, $old_site_secret);
		$this->assertNotEquals(
			_elgg_services()->crypto->getHmac('1')->getToken(),
			$old_crypto->getHmac('1')->getToken()
		);

		_elgg_testing_restore_sp();

		$this->assertSame(_elgg_services()->siteSecret, $old_site_secret);

		// due to factory, crypto is new object but with same dependencies
		$this->assertEquals(
			_elgg_services()->crypto->getHmac('1')->getToken(),
			$old_crypto->getHmac('1')->getToken()
		);
	}
}
