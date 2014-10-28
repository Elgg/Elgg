<?php

class Elgg_Amd_ConfigTest extends PHPUnit_Framework_TestCase {

	public function testCanConfigureModulePaths() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addPath('jquery', '/some/path.js');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('/some/path'), $configArray['paths']['jquery']);
	}

	public function testCanConfigureModuleShims() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addShim('jquery', array(
			'deps' => array('dep'),
			'exports' => 'jQuery',
			'random' => 'stuff',
		));

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('dep'), $configArray['shim']['jquery']['deps']);
		$this->assertEquals('jQuery', $configArray['shim']['jquery']['exports']);
		$this->assertFalse(isset($configArray['shim']['jquery']['random']));
	}

	public function testCanRequireUnregisteredAmdModules() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addDependency('jquery');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	/**
     * @expectedException InvalidParameterException
     */
	public function testThrowsOnBadShim() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addShim('bad_shim', array('invalid' => 'config'));

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	public function testCanAddModuleAsAmd() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addModule('jquery');

		$configArray = $amdConfig->getConfig();

		$this->assertEquals(array('jquery'), $configArray['deps']);
	}

	public function testCanAddModuleAsShim() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addModule('jquery.form', array('exports' => 'jquery.fn.ajaxform'));

		$configArray = $amdConfig->getConfig();

		$this->assertArrayHasKey('jquery.form', $configArray['shim']);
		$this->assertEquals(array('exports' => 'jquery.fn.ajaxform'), $configArray['shim']['jquery.form']);
	}
}
