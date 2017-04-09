<?php

namespace Elgg\Upgrade;

/**
 * @group UpgradeService
 */
class LocatorTest extends \Elgg\TestCase {

	/**
	 * @var \ElggPlugin
	 */
	private $plugin;

	public function setUp() {

		$this->setupMockServices();

		$this->plugin = $this->getMockBuilder(\ElggPlugin::class)
				->disableOriginalConstructor()
				->setMethods(['getStaticConfig', 'getID'])
				->getMock();

		$this->plugin
			->expects($this->any())
			->method('getID')
			->will($this->returnValue('test_plugin'));
	}

	public function tearDown() {

	}

	public function testRunner() {
		// Can be implemented once Plugins::find() is mocked
		$this->markTestIncomplete();
	}

	public function testCanGetPluginUpgrade() {
		$class = \Elgg\Upgrade\TestBatch::class;

		$upgrade = _elgg_services()->upgradeLocator->getUpgrade($class, 'test_plugin');
		/* @var $upgrade \ElggUpgrade */

		$this->assertNotEmpty($upgrade);

		$this->assertInstanceOf(\ElggUpgrade::class, $upgrade);
		$this->assertEquals('test_plugin:2016101900', $upgrade->id);
		$this->assertEquals("test_plugin:upgrade:2016101900:title", $upgrade->title);
		$this->assertEquals("test_plugin:upgrade:2016101900:description", $upgrade->description);

	}

	public function testCanGetExistingUpgrade() {
		// Can be implemented once PluginsSettingsTable::getEntities() is mocked
		$this->markTestIncomplete();
	}

	public function testIgnoresNonRequiredUpgrade() {
		$class = \Elgg\Upgrade\NonRequiredTestBatch::class;

		$upgrade = _elgg_services()->upgradeLocator->getUpgrade($class, 'test_plugin');

		$this->assertEmpty($upgrade);
	}
}
