<?php

namespace Elgg\Amd;

use Elgg\PluginHooksService;

/**
 * @group UnitTests
 */
class ConfigUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;
	
	/**
	 * @var Config
	 */
	protected $amdConfig;

	public function up() {
		$this->hooks = new PluginHooksService(_elgg_services()->events);
		
		$this->amdConfig = new Config($this->hooks);
	}

	public function down() {
		unset($this->hooks);
		unset($this->amdConfig);
	}

	public function testCanConfigureBaseUrl() {
		$amdConfig = $this->amdConfig;
		$amdConfig->setBaseUrl('http://foobar.com');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals('http://foobar.com', $configArray['baseUrl']);
	}

	public function testCanConfigureModulePaths() {
		$amdConfig = $this->amdConfig;
		$amdConfig->addPath('jquery', '/some/path.js');

		$this->assertTrue($amdConfig->hasModule('jquery'));

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('/some/path'), $configArray['paths']['jquery']);

		$amdConfig->removePath('jquery', '/some/path.js');
		$this->assertFalse($amdConfig->hasModule('jquery'));
	}

	public function testCanConfigureModuleShims() {
		$amdConfig = $this->amdConfig;
		$amdConfig->addShim('jquery', array(
			'deps' => array('dep'),
			'exports' => 'jQuery',
			'random' => 'stuff',
		));

		$this->assertTrue($amdConfig->hasShim('jquery'));
		$this->assertTrue($amdConfig->hasModule('jquery'));

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('dep'), $configArray['shim']['jquery']['deps']);
		$this->assertEquals('jQuery', $configArray['shim']['jquery']['exports']);
		$this->assertFalse(isset($configArray['shim']['jquery']['random']));

		$amdConfig->removeShim('jquery');

		$this->assertFalse($amdConfig->hasShim('jquery'));
		$this->assertFalse($amdConfig->hasModule('jquery'));
	}

	public function testCanRequireUnregisteredAmdModules() {
		$amdConfig = $this->amdConfig;
		$amdConfig->addDependency('jquery');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);

		$this->assertTrue($amdConfig->hasDependency('jquery'));
		$this->assertTrue($amdConfig->hasModule('jquery'));

		$amdConfig->removeDependency('jquery');
		$this->assertFalse($amdConfig->hasDependency('jquery'));
		$this->assertFalse($amdConfig->hasModule('jquery'));
	}

	/**
	 * @expectedException \InvalidParameterException
	 */
	public function testThrowsOnBadShim() {
		$amdConfig = $this->amdConfig;
		$amdConfig->addShim('bad_shim', array('invalid' => 'config'));

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	public function testCanAddModuleAsAmd() {
		$amdConfig = $this->amdConfig;
		$amdConfig->addModule('jquery');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	public function testCanAddModuleAsShim() {
		$amdConfig = $this->amdConfig;
		$amdConfig->addModule('jquery.form', array(
			'url' => 'http://foobar.com',
			'exports' => 'jquery.fn.ajaxform',
			'deps' => array('jquery')
		));

		$configArray = $amdConfig->getConfig();

		$this->assertArrayHasKey('jquery.form', $configArray['shim']);
		$this->assertEquals(array(
			'exports' => 'jquery.fn.ajaxform',
			'deps' => array('jquery')
				), $configArray['shim']['jquery.form']);

		$this->assertArrayHasKey('jquery.form', $configArray['paths']);
		$this->assertEquals(array('http://foobar.com'), $configArray['paths']['jquery.form']);

		$this->assertTrue($amdConfig->hasModule('jquery.form'));
		$this->assertTrue($amdConfig->hasShim('jquery.form'));

		$amdConfig->removeModule('jquery.form');
		$this->assertFalse($amdConfig->hasModule('jquery.form'));
		$this->assertFalse($amdConfig->hasShim('jquery.form'));
	}

	public function testGetConfigTriggersTheConfigAmdPluginHook() {
		$amdConfig = $this->amdConfig;

		$test_input = ['test' => 'test_' . time()];

		$this->hooks->registerHandler('config', 'amd', function() use ($test_input) {
			return $test_input;
		});

		$config = $amdConfig->getConfig();
		$this->assertEquals($test_input, $config);
	}

}
