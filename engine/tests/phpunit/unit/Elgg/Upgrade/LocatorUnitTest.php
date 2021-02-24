<?php

namespace Elgg\Upgrade;

use ElggUpgrade;
use Elgg\Helpers\Upgrade\TestBatch;
use Elgg\Helpers\Upgrade\NonRequiredTestBatch;

/**
 * @group UpgradeService
 * @group UnitTests
 */
class LocatorUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggPlugin
	 */
	private $plugin;

	public function up() {
		$this->plugin = $this->getMockBuilder(\ElggPlugin::class)
			->disableOriginalConstructor()
			->setMethods(['getStaticConfig', 'getID'])
			->getMock();

		$this->plugin
			->expects($this->any())
			->method('getID')
			->will($this->returnValue('test_plugin'));
	}

	public function down() {

	}

	public function testRunner() {
		// Can be implemented once Plugins::find() is mocked
		$this->markTestIncomplete();
	}

	public function testCanGetPluginUpgrade() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$class = TestBatch::class;
			
			$upgrade = _elgg_services()->upgradeLocator->getUpgrade($class, 'test_plugin');
			/* @var $upgrade ElggUpgrade */
	
			$this->assertNotEmpty($upgrade);
	
			$this->assertInstanceOf(ElggUpgrade::class, $upgrade);
			$this->assertEquals('test_plugin:2016101900', $upgrade->id);
			$this->assertEquals("test_plugin:upgrade:2016101900:title", $upgrade->title);
			$this->assertEquals("test_plugin:upgrade:2016101900:description", $upgrade->description);
		});
	}

	public function testIgnoresNonRequiredUpgrade() {
		$class = NonRequiredTestBatch::class;

		$upgrade = _elgg_services()->upgradeLocator->getUpgrade($class, 'test_plugin');

		$batch = $upgrade->getBatch();

		$this->assertTrue($batch->shouldBeSkipped());
	}
}
