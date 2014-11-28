<?php
/**
 * Elgg Plugins Test
 *
 * @package Elgg.Core
 * @subpackage Plugins.Test
 */
class ElggCorePluginsAPITest extends \ElggCoreUnitTest {
	// 1.8 manifest object
	var $manifest18;

	// 1.8 package at test_files/plugin_18/
	var $package18;

	// 1.7 manifest object
	var $manifest17;

	// 1.7 package at test_files/plugin_17/
	var $package17;
	
	public function __construct() {
		parent::__construct();

		$this->manifest18 = new \ElggPluginManifest(get_config('path') . 'engine/tests/test_files/plugin_18/manifest.xml', 'plugin_test_18');
		$this->manifest17 = new \ElggPluginManifest(get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml', 'plugin_test_17');

		$this->package18 = new \ElggPluginPackage(get_config('path') . 'engine/tests/test_files/plugin_18');
		$this->package17 = new \ElggPluginPackage(get_config('path') . 'engine/tests/test_files/plugin_17');
	}

	// generic tests
	public function testElggPluginManifestFromString() {
		$manifest_file = file_get_contents(get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml');
		$manifest = new \ElggPluginManifest($manifest_file);

		$this->assertIsA($manifest, '\ElggPluginManifest');
	}

	public function testElggPluginManifestFromFile() {
		$file = get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml';
		$manifest = new \ElggPluginManifest($file);

		$this->assertIsA($manifest, '\ElggPluginManifest');
	}

	public function testElggPluginManifestFromXMLEntity() {
		$manifest_file = file_get_contents(get_config('path') . 'engine/tests/test_files/plugin_17/manifest.xml');
		$xml = new \ElggXMLElement($manifest_file);
		$manifest = new \ElggPluginManifest($xml);

		$this->assertIsA($manifest, '\ElggPluginManifest');
	}

	// exact manifest values
	// 1.8 interface
	public function testElggPluginManifest18() {
		$manifest_array = array(
			'name' => 'Test Manifest',
			'author' => 'Anyone',
			'version' => '1.0',
			'blurb' => 'A concise description.',
			'description' => 'A longer, more interesting description.',
			'website' => 'http://www.elgg.org/',
			'repository' => 'https://github.com/Elgg/Elgg',
			'bugtracker' => 'https://github.com/elgg/elgg/issues',
			'donations' => 'http://elgg.org/supporter.php',
			'copyright' => '(C) Elgg Foundation 2011',
			'license' => 'GNU General Public License version 2',

			'requires' => array(
				array('type' => 'elgg_version', 'version' => '3009030802', 'comparison' => 'lt'),
				array('type' => 'elgg_release', 'version' => '1.8-svn'),
				array('type' => 'php_extension', 'name' => 'gd'),
				array('type' => 'php_ini', 'name' => 'short_open_tag', 'value' => 'off'),
				array('type' => 'php_version', 'version' => '5.6'),
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
			
			'contributor' => array(
				array('name' => 'Evan Winslow', 'email' => 'evan@elgg.org', 'website' => 'http://evanwinslow.com/', 'username' => 'ewinslow', 'description' => "Description of Evan's role in the project"),
				array('name' => 'Cash Costello', 'email' => 'cash@elgg.org', 'description' => "Description of Cash's role in the project"),
			),

			'category' => array(
				'Admin', 'ServiceAPI'
			),

			'conflicts' => array(
				array('type' => 'plugin', 'name' => 'profile_api', 'version' => '1.0')
			),

			'provides' => array(
				array('type' => 'plugin', 'name' => 'profile_api', 'version' => '1.3'),
				array('type' => 'php_extension', 'name' => 'big_math', 'version' => '1.0')
			),

			'suggests' => array(
				array('type' => 'plugin', 'name' => 'facebook_connect', 'version' => '1.0'),
			),

			// string because we are reading from a file
			'activate_on_install' => 'true',
		);

		$this->assertIdentical($this->manifest18->getManifest(), $manifest_array);
	}

	public function testElggPluginManifest17() {
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

		$this->assertIdentical($this->manifest17->getManifest(), $manifest_array);
	}


	public function testElggPluginManifestGetApiVersion() {
		$this->assertEqual($this->manifest18->getApiVersion(), 1.8);
		$this->assertEqual($this->manifest17->getApiVersion(), 1.7);
	}

	public function testElggPluginManifestGetPluginID() {
		$this->assertEqual($this->manifest18->getPluginID(), 'plugin_test_18');
		$this->assertEqual($this->manifest17->getPluginID(), 'plugin_test_17');
	}


	// normalized attributes
	public function testElggPluginManifestGetName() {
		$this->assertEqual($this->manifest18->getName(), 'Test Manifest');
		$this->assertEqual($this->manifest17->getName(), 'Plugin Test 17');
	}

	public function testElggPluginManifestGetAuthor() {
		$this->assertEqual($this->manifest18->getAuthor(), 'Anyone');
		$this->assertEqual($this->manifest17->getAuthor(), 'Anyone');
	}

	public function testElggPluginManifestGetVersion() {
		$this->assertEqual($this->manifest18->getVersion(), 1.0);
		$this->assertEqual($this->manifest17->getVersion(), 1.0);
	}

	public function testElggPluginManifestGetBlurb() {
		$this->assertEqual($this->manifest18->getBlurb(), 'A concise description.');
		$this->assertEqual($this->manifest17->getBlurb(), 'A 1.7-style manifest.');
	}

	public function testElggPluginManifestGetWebsite() {
		$this->assertEqual($this->manifest18->getWebsite(), 'http://www.elgg.org/');
		$this->assertEqual($this->manifest17->getWebsite(), 'http://www.elgg.org/');
	}
	
	public function testElggPluginManifestGetRepository() {
		$this->assertEqual($this->manifest18->getRepositoryURL(), 'https://github.com/Elgg/Elgg');
		$this->assertEqual($this->manifest17->getRepositoryURL(), '');
	}
	
		public function testElggPluginManifestGetBugtracker() {
		$this->assertEqual($this->manifest18->getBugTrackerURL(), 'https://github.com/elgg/elgg/issues');
		$this->assertEqual($this->manifest17->getBugTrackerURL(), '');
	}
	
		public function testElggPluginManifestGetDonationsPage() {
		$this->assertEqual($this->manifest18->getDonationsPageURL(), 'http://elgg.org/supporter.php');
		$this->assertEqual($this->manifest17->getDonationsPageURL(), '');
	}

	public function testElggPluginManifestGetCopyright() {
		$this->assertEqual($this->manifest18->getCopyright(), '(C) Elgg Foundation 2011');
		$this->assertEqual($this->manifest18->getCopyright(), '(C) Elgg Foundation 2011');
	}

	public function testElggPluginManifestGetLicense() {
		$this->assertEqual($this->manifest18->getLicense(), 'GNU General Public License version 2');
		$this->assertEqual($this->manifest17->getLicense(), 'GNU General Public License version 2');
	}


	public function testElggPluginManifestGetRequires() {
		$requires = array(
			array('type' => 'elgg_version', 'version' => '3009030802', 'comparison' => 'lt'),
			array('type' => 'elgg_release', 'version' => '1.8-svn', 'comparison' => 'ge'),
			array('type' => 'php_extension', 'name' => 'gd', 'version' => '', 'comparison' => '='),
			array('type' => 'php_ini', 'name' => 'short_open_tag', 'value' => 0, 'comparison' => '='),
			array('type' => 'php_version', 'version' => '5.6', 'comparison' => 'ge'),
			array('type' => 'php_extension', 'name' => 'made_up', 'version' => '1.0', 'comparison' => '='),
			array('type' => 'plugin', 'name' => 'fake_plugin', 'version' => '1.0', 'comparison' => 'ge'),
			array('type' => 'plugin', 'name' => 'profile', 'version' => '1.0', 'comparison' => 'ge'),
			array('type' => 'plugin', 'name' => 'profile_api', 'version' => '1.3', 'comparison' => 'lt'),
			array('type' => 'priority', 'priority' => 'after', 'plugin' => 'profile'),
		);

		$this->assertIdentical($this->package18->getManifest()->getRequires(), $requires);

		$requires = array(
			array('type' => 'elgg_version', 'version' => '2009030702', 'comparison' => 'ge')
		);

		$this->assertIdentical($this->package17->getManifest()->getRequires(), $requires);
	}

	public function testElggPluginManifestGetSuggests() {
		$suggests = array(
			array('type' => 'plugin', 'name' => 'facebook_connect', 'version' => '1.0', 'comparison' => 'ge'),
		);

		$this->assertIdentical($this->package18->getManifest()->getSuggests(), $suggests);

		$suggests = array();

		$this->assertIdentical($this->package17->getManifest()->getSuggests(), $suggests);
	}

	public function testElggPluginManifestGetDescription() {
		$this->assertEqual($this->package18->getManifest()->getDescription(), 'A longer, more interesting description.');
		$this->assertEqual($this->package17->getManifest()->getDescription(), 'A 1.7-style manifest.');
	}

	public function testElggPluginManifestGetCategories() {
		$categories = array(
			'Admin', 'ServiceAPI'
		);

		$this->assertIdentical($this->package18->getManifest()->getCategories(), $categories);
		$this->assertIdentical($this->package17->getManifest()->getCategories(), array());
	}

	public function testElggPluginManifestGetScreenshots() {
		$screenshots = array(
			array('description' => 'Fun things to do 1', 'path' => 'graphics/plugin_ss1.png'),
			array('description' => 'Fun things to do 2', 'path' => 'graphics/plugin_ss2.png'),
		);

		$this->assertIdentical($this->package18->getManifest()->getScreenshots(), $screenshots);
		$this->assertIdentical($this->package17->getManifest()->getScreenshots(), array());
	}
	
	public function testElggPluginManifestGetContributors() {
		$contributors = array(
			array('name' => 'Evan Winslow', 'email' => 'evan@elgg.org', 'website' => 'http://evanwinslow.com/', 'username' => 'ewinslow', 'description' => "Description of Evan's role in the project"),
			array('name' => 'Cash Costello', 'email' => 'cash@elgg.org', 'website' => '', 'username' => '', 'description' => "Description of Cash's role in the project"),
		);

		$this->assertIdentical($this->package18->getManifest()->getContributors(), $contributors);
		$this->assertIdentical($this->package17->getManifest()->getContributors(), array());
	}

	public function testElggPluginManifestGetProvides() {
		$provides = array(
			array('type' => 'plugin', 'name' => 'profile_api', 'version' => '1.3'),
			array('type' => 'php_extension', 'name' => 'big_math', 'version' => '1.0'),
			array('type' => 'plugin', 'name' => 'plugin_18', 'version' => '1.0')
		);

		$this->assertIdentical($this->package18->getManifest()->getProvides(), $provides);


		$provides = array(
			array('type' => 'plugin', 'name' => 'plugin_17', 'version' => '1.0')
		);

		$this->assertIdentical($this->package17->getManifest()->getProvides(), $provides);
	}

	public function testElggPluginManifestGetConflicts() {
		$conflicts = array(
			array(
				'type' => 'plugin',
				'name' => 'profile_api',
				'version' => '1.0',
				'comparison' => '='
			)
		);

		$this->assertIdentical($this->manifest18->getConflicts(), $conflicts);
		$this->assertIdentical($this->manifest17->getConflicts(), array());
	}

	public function testElggPluginManifestGetActivateOnInstall() {
		$this->assertIdentical($this->manifest18->getActivateOnInstall(), true);
	}

	// \ElggPluginPackage
	public function testElggPluginPackageDetectIDFromPath() {
		$this->assertEqual($this->package18->getID(), 'plugin_18');
	}

	public function testElggPluginPackageDetectIDFromPluginID() {
		$package = new \ElggPluginPackage('profile');
		$this->assertEqual($package->getID(), 'profile');
	}
	
	// \ElggPlugin
	public function testElggPluginIsValid() {
		
		$test_plugin = new \ElggPlugin('profile');
		
		$this->assertIdentical(true, $test_plugin->isValid());
	}
	
	public function testElggPluginGetID() {
		
		$test_plugin = new \ElggPlugin('profile');
		
		$this->assertIdentical('profile', $test_plugin->getID());
	}
}
