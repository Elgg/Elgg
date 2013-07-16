<?php

class Elgg_Amd_ConfigTest extends PHPUnit_Framework_TestCase {
	
	public function testCanConfigureModulePaths() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->setPath('jquery', '/some/path.js');
		
		$configArray = $amdConfig->getConfig();
		
		$this->assertEquals('/some/path', $configArray['paths']['jquery']);
	}
	
	public function testCanConfigureModuleShims() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->setShim('jquery', array(
			'deps' => array(),
			'exports' => 'jQuery',
			'random' => 'stuff',
		));
		
		$configArray = $amdConfig->getConfig();
		
		$this->assertEquals('jQuery', $configArray['shim']['jquery']['exports']);
		$this->assertFalse(isset($configArray['shim']['jquery']['random']));
	}
	
	public function testCanRequireUnregisteredAmdModules() {
		$amdConfig = new Elgg_Amd_Config();
		$amdConfig->addDependency('jquery');
		
		$configArray = $amdConfig->getConfig();
		
		$this->assertEquals(array('jquery'), $configArray['deps']);
	}
}
