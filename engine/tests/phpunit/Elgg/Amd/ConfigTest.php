<?php
namespace Elgg\Amd;


class ConfigTest extends \PHPUnit_Framework_TestCase {

	public function testCanConfigureBaseUrl() {
		$amdConfig = new \Elgg\Amd\Config();
		$amdConfig->setBaseUrl('http://foobar.com');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals('http://foobar.com', $configArray['baseUrl']);
	}

	public function testCanConfigureModulePaths() {
		$amdConfig = new \Elgg\Amd\Config();
		$amdConfig->addPath('jquery', '/some/path.js');

		$this->assertTrue($amdConfig->hasModule('jquery'));
		
		$configArray = $amdConfig->getConfig();
		
		$this->assertEquals(array('/some/path'), $configArray['paths']['jquery']);

		$amdConfig->removePath('jquery', '/some/path.js');
		$this->assertFalse($amdConfig->hasModule('jquery'));
	}
	
	public function testCanConfigureModuleShims() {
		$amdConfig = new \Elgg\Amd\Config();
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
		$amdConfig = new \Elgg\Amd\Config();
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
		$amdConfig = new \Elgg\Amd\Config();
		$amdConfig->addShim('bad_shim', array('invalid' => 'config'));

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	public function testCanAddModuleAsAmd() {
		$amdConfig = new \Elgg\Amd\Config();
		$amdConfig->addModule('jquery');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	public function testCanAddModuleAsShim() {
		$amdConfig = new \Elgg\Amd\Config();
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
}

