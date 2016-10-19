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
			->method('getStaticConfig')
			->will($this->returnCallback(function($name) {
				if ($name == 'upgrades') {
					return [\Elgg\Upgrade\TestBatch::class];
				}
			}));

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

	public function testCanGetPluginUpgrades() {

		$upgrades = _elgg_services()->upgradeLocator->getUpgrades($this->plugin);

		$this->assertNotEmpty($upgrades);
		
		$upgrade = array_shift($upgrades);
		/* @var $upgrade \ElggUpgrade */

		$this->assertInstanceOf(\ElggUpgrade::class, $upgrade);
		$this->assertEquals('test_plugin:2016101900', $upgrade->id);
		$this->assertEquals("test_plugin:upgrade:2016101900:title", $upgrade->title);
		$this->assertEquals("test_plugin:upgrade:2016101900:description", $upgrade->description);
		
	}

	public function testCanGetExistingUpgrade() {
		// Can be implemented once PluginsSettingsTable::getEntities() is mocked
		$this->markTestIncomplete();
	}
}
