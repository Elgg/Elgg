<?php
/**
 * This is a UnitTestCase because we do not want config data to be
 * manipulated in a real database during the testSetsBackupSiteSecretFactory test
 */

namespace Elgg\Database;

use Elgg\UnitTestCase;
use Elgg\Di\InternalContainer;

class InternalContainerUnitTest extends UnitTestCase {
	
	public function testSiteSecretCreatedFromConfig() {
		
		$config = self::getTestingConfig();
		$config->{SiteSecret::CONFIG_KEY} = md5('bar');
		
		$services = InternalContainer::factory(['config' => $config]);
		
		$this->assertEmpty($services->config->{SiteSecret::CONFIG_KEY});
		
		$this->assertInstanceOf(SiteSecret::class, $services->siteSecret);
		$this->assertEquals(md5('bar'), $services->siteSecret->get());
	}

	public function testSiteSecretCreatedFromDatabase() {
		$config_table = _elgg_services()->configTable;
		$config_table->set(SiteSecret::CONFIG_KEY, md5('foo'));
		
		$services = InternalContainer::factory(['config' => self::getTestingConfig()]);
		
		$services->set('configTable', $config_table);

		$this->assertEmpty($services->config->{SiteSecret::CONFIG_KEY});
		
		$this->assertInstanceOf(SiteSecret::class, $services->siteSecret);
		$this->assertEquals(md5('foo'), $services->siteSecret->get());
	}
}
