<?php
/**
 *  Copyright (C) 2011 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  IMPORTANT: The tests in this file were ported from the original
 *  elgg engine tests. Please see Elgg's README.txt, COPYRIGHT.txt 
 *  and CONTRIBUTORS.txt for copyright and contributor information.  
 */
require_once(dirname(__FILE__) . '/../model/ElggTestCase.php');

class ElggCorePluginsAPITest extends ElggTestCase
{
	// 1.8 manifest object
	var $manifest18;

	// 1.8 package at test_files/plugin_18/
	var $package18;

	// 1.7 manifest object
	var $manifest17;

	// 1.7 package at test_files/plugin_17/
	var $package17;

	public function setUp()
	{
		parent::setUp();

		$this->manifest18 = new ElggPluginManifest(get_config('path') . 'engine/tests/test_files/plugin_18/manifest.xml', 'plugin_test_18');
		$this->manifest17 = new ElggPluginManifest(get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml', 'plugin_test_17');

		$this->package18 = new ElggPluginPackage(get_config('path') . 'engine/tests/test_files/plugin_18');
		$this->package17 = new ElggPluginPackage(get_config('path') . 'engine/tests/test_files/plugin_17');
	}

	// generic tests
	public function testElggPluginManifestFromString() 
	{
		$manifest_file = file_get_contents(get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml');
		$manifest = new ElggPluginManifest($manifest_file);

		$this->assertInstanceOf('ElggPluginManifest', $manifest);
	}

	public function testElggPluginManifestFromFile() 
	{
		$file = get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml';
		$manifest = new ElggPluginManifest($file);

		$this->assertInstanceOf('ElggPluginManifest', $manifest);
	}

	public function testElggPluginManifestFromXMLEntity() 
	{
		$xml = xml_to_object($manifest_file = file_get_contents(get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml'));
		$manifest = new ElggPluginManifest($xml);

		$this->assertInstanceOf('ElggPluginManifest', $manifest);
	}

	// exact manifest values
	// 1.8 interface
	public function testElggPluginManifest18() 
	{
		$manifest_array = array(
			'name' => 'Test Manifest',
			'author' => 'Anyone',
			'version' => '1.0',
			'blurb' => 'A concise description.',
			'description' => 'A longer, more interesting description.',
			'website' => 'http://www.elgg.org/',
			'copyright' => '(C) Elgg Foundation 2011',
			'license' => 'GNU General Public License version 2',

			'requires' => array(
				array('type' => 'elgg_version', 'version' => '3009030802', 'comparison' => 'lt'),
				array('type' => 'elgg_release', 'version' => '1.8-svn'),
				array('type' => 'php_extension', 'name' => 'gd'),
				array('type' => 'php_ini', 'name' => 'short_open_tag', 'value' => 'off'),
				array('type' => 'php_extension', 'name' => 'made_up', 'version' => '1.0'),
				array('type' => 'plugin', 'name' => 'fake_plugin', 'version' => '1.0'),
				array('type' => 'plugin', 'name' => 'profile', 'version' => '1.0'),
				array('type' => 'plugin', 'name' => 'profile_api', 'version' => '1.3', 'comparison' => 'lt'),
				array('type' => 'priority', 'priority' => 'after', 'plugin' => 'profile'),
			),

			'screenshot' => array(
				array('description' => 'Fun things to do 1', 'path' => 'graphics/plugin_ss1.png'),
				array('description' => 'Fun things to do 2', 'path' => 'graphics/plugin_ss2.png'),
			),

			'category' => array(
				'Admin', 'ServiceAPI'
			),

			'conflicts' => array(
				array('type' => 'plugin', 'name' => 'profile_api', 'version' => 1.0)
			),

			'provides' => array(
				array('type' => 'plugin', 'name' => 'profile_api', 'version' => 1.3),
				array('type' => 'php_extension', 'name' => 'big_math', 'version' => 1.0)
			),

			'suggests' => array(
				array('type' => 'plugin', 'name' => 'facebook_connect', 'version' => 1.0),
			),
			//Used to be: 'activate_on_install' => true
			//@link http://trac.elgg.org/ticket/4133			
			'activate_on_install' => 'true'
		);

		$this->assertEquals($this->manifest18->getManifest(), $manifest_array);
	}

	public function testElggPluginManifest17() 
	{
		$manifest_array = array(
			'author' => 'Anyone',
			'version' => '1.0',
			'description' => 'A 1.7-style manifest.',
			'website' => 'http://www.elgg.org/',
			'copyright' => '(C) Elgg Foundation 2011',
			'license' => 'GNU General Public License version 2',
			'elgg_version' => '2009030702',
			'name' => 'Plugin Test 17',
		);

		$this->assertEquals($this->manifest17->getManifest(), $manifest_array);
	}


	public function testElggPluginManifestGetApiVersion() 
	{
		$this->assertEquals($this->manifest18->getApiVersion(), 1.8);
		$this->assertEquals($this->manifest17->getApiVersion(), 1.7);
	}

	public function testElggPluginManifestGetPluginID() 
	{
		$this->assertEquals($this->manifest18->getPluginID(), 'plugin_test_18');
		$this->assertEquals($this->manifest17->getPluginID(), 'plugin_test_17');
	}


	// normalized attributes
	public function testElggPluginManifestGetName() 
	{
		$this->assertEquals($this->manifest18->getName(), 'Test Manifest');
		$this->assertEquals($this->manifest17->getName(), 'Plugin Test 17');
	}

	public function testElggPluginManifestGetAuthor() 
	{
		$this->assertEquals($this->manifest18->getAuthor(), 'Anyone');
		$this->assertEquals($this->manifest17->getAuthor(), 'Anyone');
	}

	public function testElggPluginManifestGetVersion() 
	{
		$this->assertEquals($this->manifest18->getVersion(), 1.0);
		$this->assertEquals($this->manifest17->getVersion(), 1.0);
	}

	public function testElggPluginManifestGetBlurb() 
	{
		$this->assertEquals($this->manifest18->getBlurb(), 'A concise description.');
		$this->assertEquals($this->manifest17->getBlurb(), 'A 1.7-style manifest.');
	}

	public function testElggPluginManifestGetWebsite() 
	{
		$this->assertEquals($this->manifest18->getWebsite(), 'http://www.elgg.org/');
		$this->assertEquals($this->manifest17->getWebsite(), 'http://www.elgg.org/');
	}

	public function testElggPluginManifestGetCopyright() 
	{
		$this->assertEquals($this->manifest18->getCopyright(), '(C) Elgg Foundation 2011');
		$this->assertEquals($this->manifest18->getCopyright(), '(C) Elgg Foundation 2011');
	}

	public function testElggPluginManifestGetLicense() 
	{
		$this->assertEquals($this->manifest18->getLicense(), 'GNU General Public License version 2');
		$this->assertEquals($this->manifest17->getLicense(), 'GNU General Public License version 2');
	}


	public function testElggPluginManifestGetRequires() 
	{
		$requires = array(
			array('type' => 'elgg_version', 'version' => '3009030802', 'comparison' => 'lt'),
			array('type' => 'elgg_release', 'version' => '1.8-svn', 'comparison' => 'ge'),
			array('type' => 'php_extension', 'name' => 'gd', 'version' => '', 'comparison' => '='),
			//Used to be: array('type' => 'php_ini', 'name' => 'short_open_tag', 'value' => 'off', 'comparison' => '='),
			//@link http://trac.elgg.org/ticket/4134
			array('type' => 'php_ini', 'name' => 'short_open_tag', 'value' => 0, 'comparison' => '='),
			array('type' => 'php_extension', 'name' => 'made_up', 'version' => '1.0', 'comparison' => '='),
			array('type' => 'plugin', 'name' => 'fake_plugin', 'version' => '1.0', 'comparison' => 'ge'),
			array('type' => 'plugin', 'name' => 'profile', 'version' => '1.0', 'comparison' => 'ge'),
			array('type' => 'plugin', 'name' => 'profile_api', 'version' => '1.3', 'comparison' => 'lt'),
			array('type' => 'priority', 'priority' => 'after', 'plugin' => 'profile'),
		);

		$this->assertEquals($this->package18->getManifest()->getRequires(), $requires);

		$requires = array(
			array('type' => 'elgg_version', 'version' => '2009030702', 'comparison' => 'ge')
		);

		$this->assertEquals($this->package17->getManifest()->getRequires(), $requires);
	}

	public function testElggPluginManifestGetSuggests() 
	{
		$suggests = array(
			array('type' => 'plugin', 'name' => 'facebook_connect', 'version' => '1.0', 'comparison' => 'ge'),
		);

		$this->assertEquals($this->package18->getManifest()->getSuggests(), $suggests);

		$suggests = array();

		$this->assertEquals($this->package17->getManifest()->getSuggests(), $suggests);
	}

	public function testElggPluginManifestGetDescription() 
	{
		$this->assertEquals($this->package18->getManifest()->getDescription(), 'A longer, more interesting description.');
		$this->assertEquals($this->package17->getManifest()->getDescription(), 'A 1.7-style manifest.');
	}

	public function testElggPluginManifestGetCategories() 
	{
		$categories = array(
			'Admin', 'ServiceAPI'
		);

		$this->assertEquals($this->package18->getManifest()->getCategories(), $categories);
		$this->assertEquals($this->package17->getManifest()->getCategories(), array());
	}

	public function testElggPluginManifestGetScreenshots() 
	{
		$screenshots = array(
			array('description' => 'Fun things to do 1', 'path' => 'graphics/plugin_ss1.png'),
			array('description' => 'Fun things to do 2', 'path' => 'graphics/plugin_ss2.png'),
		);

		$this->assertEquals($this->package18->getManifest()->getScreenshots(), $screenshots);
		$this->assertEquals($this->package17->getManifest()->getScreenshots(), array());
	}

	public function testElggPluginManifestGetProvides() 
	{
		$provides = array(
			array('type' => 'plugin', 'name' => 'profile_api', 'version' => 1.3),
			array('type' => 'php_extension', 'name' => 'big_math', 'version' => 1.0),
			array('type' => 'plugin', 'name' => 'plugin_18', 'version' => 1.0)
		);

		$this->assertEquals($this->package18->getManifest()->getProvides(), $provides);


		$provides = array(
			array('type' => 'plugin', 'name' => 'plugin_17', 'version' => '1.0')
		);

		$this->assertEquals($this->package17->getManifest()->getProvides(), $provides);
	}

	public function testElggPluginManifestGetConflicts() 
	{
		$conflicts = array(
			array(
				'type' => 'plugin',
				'name' => 'profile_api',
				'version' => '1.0',
				'comparison' => '='
			)
		);

		$this->assertEquals($this->manifest18->getConflicts(), $conflicts);
		$this->assertEquals($this->manifest17->getConflicts(), array());
	}

	public function testElggPluginManifestGetActivateOnInstall() 
	{
		$this->assertEquals($this->manifest18->getActivateOnInstall(), true);
	}

	// ElggPluginPackage
	public function testElggPluginPackageDetectIDFromPath() 
	{
		$this->assertEquals($this->package18->getID(), 'plugin_18');
	}

	public function testElggPluginPackageDetectIDFromPluginID() 
	{
		$package = new ElggPluginPackage('profile');
		$this->assertEquals($package->getID(), 'profile');
	}
}