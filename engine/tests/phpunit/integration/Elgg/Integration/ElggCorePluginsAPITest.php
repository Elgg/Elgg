<?php

namespace Elgg\Integration;

use ElggPlugin;
use ElggPluginManifest;
use ElggPluginPackage;

/**
 * Elgg Plugins Test
 *
 * @group IntegrationTests
 * @group Plugins
 * @group PluginManifest
 */
class ElggCorePluginsAPITest extends \Elgg\IntegrationTestCase {

	/**
	 * @var ElggPluginManifest
	 */
	protected $manifest18;

	/**
	 * @var ElggPluginPackage
	 */
	protected $package18;

	public function up() {
		$this->manifest18 = new ElggPluginManifest($this->normalizeTestFilePath('plugin_18/manifest.xml'), 'plugin_test_18');
		$this->package18 = new ElggPluginPackage($this->normalizeTestFilePath('plugin_18'));
	}

	public function down() {

	}

	// generic tests
	public function testElggPluginManifestFromString() {
		$manifest_file = file_get_contents($this->normalizeTestFilePath('plugin_18/manifest.xml'));
		$manifest = new ElggPluginManifest($manifest_file);

		$this->assertInstanceOf(ElggPluginManifest::class, $manifest);
	}

	public function testElggPluginManifestFromFile() {
		$file = $this->normalizeTestFilePath('plugin_18/manifest.xml');
		$manifest = new ElggPluginManifest($file);

		$this->assertInstanceOf(ElggPluginManifest::class, $manifest);
	}

	public function testElggPluginManifestFromXMLEntity() {
		$manifest_file = file_get_contents($this->normalizeTestFilePath('plugin_18/manifest.xml'));
		$xml = new \ElggXMLElement($manifest_file);
		$manifest = new ElggPluginManifest($xml);

		$this->assertInstanceOf(ElggPluginManifest::class, $manifest);
	}

	// exact manifest values
	// 1.8 interface
	public function testElggPluginManifest18() {
		$manifest_array = [
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

			'requires' => [
				[
					'type' => 'elgg_release',
					'version' => '1.8-svn'
				],
				[
					'type' => 'php_extension',
					'name' => 'gd'
				],
				[
					'type' => 'php_ini',
					'name' => 'short_open_tag',
					'value' => 'off'
				],
				[
					'type' => 'php_version',
					'version' => '5.6'
				],
				[
					'type' => 'php_extension',
					'name' => 'made_up',
					'version' => '1.0'
				],
				[
					'type' => 'plugin',
					'name' => 'fake_plugin',
					'version' => '1.0'
				],
				[
					'type' => 'plugin',
					'name' => 'profile',
					'version' => '1.0'
				],
				[
					'type' => 'plugin',
					'name' => 'profile_api',
					'version' => '1.3',
					'comparison' => 'lt'
				],
				[
					'type' => 'priority',
					'priority' => 'after',
					'plugin' => 'profile'
				],
			],

			'screenshot' => [
				[
					'description' => 'Fun things to do 1',
					'path' => 'graphics/plugin_ss1.png'
				],
				[
					'description' => 'Fun things to do 2',
					'path' => 'graphics/plugin_ss2.png'
				],
			],

			'contributor' => [
				[
					'name' => 'Evan Winslow',
					'email' => 'evan@elgg.org',
					'website' => 'http://evanwinslow.com/',
					'username' => 'ewinslow',
					'description' => "Description of Evan's role in the project"
				],
				[
					'name' => 'Cash Costello',
					'email' => 'cash@elgg.org',
					'description' => "Description of Cash's role in the project"
				],
			],

			'category' => [
				'Admin',
				'ServiceAPI'
			],

			'conflicts' => [
				[
					'type' => 'plugin',
					'name' => 'profile_api',
					'version' => '1.0'
				]
			],

			'provides' => [
				[
					'type' => 'plugin',
					'name' => 'profile_api',
					'version' => '1.3'
				],
				[
					'type' => 'php_extension',
					'name' => 'big_math',
					'version' => '1.0'
				]
			],

			'suggests' => [
				[
					'type' => 'plugin',
					'name' => 'facebook_connect',
					'version' => '1.0'
				],
			],

			// string because we are reading from a file
			'activate_on_install' => 'true',
		];

		$this->assertEquals($manifest_array, $this->manifest18->getManifest());
	}

	public function testElggPluginManifestGetApiVersion() {
		$this->assertEquals(1.8, $this->manifest18->getApiVersion());
	}

	public function testElggPluginManifestGetPluginID() {
		$this->assertEquals('plugin_test_18', $this->manifest18->getPluginID());
	}

	// normalized attributes
	public function testElggPluginManifestGetName() {
		$this->assertEquals('Test Manifest', $this->manifest18->getName());
	}

	public function testElggPluginManifestGetAuthor() {
		$this->assertEquals('Anyone', $this->manifest18->getAuthor());
	}

	public function testElggPluginManifestGetVersion() {
		$this->assertEquals(1.0, $this->manifest18->getVersion());
	}

	public function testElggPluginManifestGetBlurb() {
		$this->assertEquals('A concise description.', $this->manifest18->getBlurb());
	}

	public function testElggPluginManifestGetWebsite() {
		$this->assertEquals('http://www.elgg.org/', $this->manifest18->getWebsite());
	}

	public function testElggPluginManifestGetRepository() {
		$this->assertEquals('https://github.com/Elgg/Elgg', $this->manifest18->getRepositoryURL());
	}

	public function testElggPluginManifestGetBugtracker() {
		$this->assertEquals('https://github.com/elgg/elgg/issues', $this->manifest18->getBugTrackerURL());
	}

	public function testElggPluginManifestGetDonationsPage() {
		$this->assertEquals('http://elgg.org/supporter.php', $this->manifest18->getDonationsPageURL());
	}

	public function testElggPluginManifestGetCopyright() {
		$this->assertEquals('(C) Elgg Foundation 2011', $this->manifest18->getCopyright());
	}

	public function testElggPluginManifestGetLicense() {
		$this->assertEquals('GNU General Public License version 2', $this->manifest18->getLicense());
	}

	public function testElggPluginManifestGetRequires() {
		$requires = [
			[
				'type' => 'elgg_release',
				'version' => '1.8-svn',
				'comparison' => 'ge'
			],
			[
				'type' => 'php_extension',
				'name' => 'gd',
				'version' => '',
				'comparison' => '='
			],
			[
				'type' => 'php_ini',
				'name' => 'short_open_tag',
				'value' => 0,
				'comparison' => '='
			],
			[
				'type' => 'php_version',
				'version' => '5.6',
				'comparison' => 'ge'
			],
			[
				'type' => 'php_extension',
				'name' => 'made_up',
				'version' => '1.0',
				'comparison' => '='
			],
			[
				'type' => 'plugin',
				'name' => 'fake_plugin',
				'version' => '1.0',
				'comparison' => 'ge'
			],
			[
				'type' => 'plugin',
				'name' => 'profile',
				'version' => '1.0',
				'comparison' => 'ge'
			],
			[
				'type' => 'plugin',
				'name' => 'profile_api',
				'version' => '1.3',
				'comparison' => 'lt'
			],
			[
				'type' => 'priority',
				'priority' => 'after',
				'plugin' => 'profile'
			],
		];

		$this->assertEquals($requires, $this->package18->getManifest()->getRequires());
	}

	public function testElggPluginManifestGetSuggests() {
		$suggests = [
			[
				'type' => 'plugin',
				'name' => 'facebook_connect',
				'version' => '1.0',
				'comparison' => 'ge'
			],
		];

		$this->assertEquals($suggests, $this->package18->getManifest()->getSuggests());
	}

	public function testElggPluginManifestGetDescription() {
		$this->assertEquals('A longer, more interesting description.', $this->package18->getManifest()->getDescription());
	}

	public function testElggPluginManifestGetCategories() {
		$categories = [
			'Admin',
			'ServiceAPI'
		];

		$this->assertEquals($categories, $this->package18->getManifest()->getCategories());
	}

	public function testElggPluginManifestGetScreenshots() {
		$screenshots = [
			[
				'description' => 'Fun things to do 1',
				'path' => 'graphics/plugin_ss1.png'
			],
			[
				'description' => 'Fun things to do 2',
				'path' => 'graphics/plugin_ss2.png'
			],
		];

		$this->assertEquals($screenshots, $this->package18->getManifest()->getScreenshots());
	}

	public function testElggPluginManifestGetContributors() {
		$contributors = [
			[
				'name' => 'Evan Winslow',
				'email' => 'evan@elgg.org',
				'website' => 'http://evanwinslow.com/',
				'username' => 'ewinslow',
				'description' => "Description of Evan's role in the project"
			],
			[
				'name' => 'Cash Costello',
				'email' => 'cash@elgg.org',
				'website' => '',
				'username' => '',
				'description' => "Description of Cash's role in the project"
			],
		];

		$this->assertEquals($contributors, $this->package18->getManifest()->getContributors());
	}

	public function testElggPluginManifestGetProvides() {
		$provides = [
			[
				'type' => 'plugin',
				'name' => 'profile_api',
				'version' => '1.3'
			],
			[
				'type' => 'php_extension',
				'name' => 'big_math',
				'version' => '1.0'
			],
			[
				'type' => 'plugin',
				'name' => 'plugin_18',
				'version' => '1.0'
			]
		];

		$this->assertEquals($provides, $this->package18->getManifest()->getProvides());
	}

	public function testElggPluginManifestGetConflicts() {
		$conflicts = [
			[
				'type' => 'plugin',
				'name' => 'profile_api',
				'version' => '1.0',
				'comparison' => '='
			]
		];

		$this->assertEquals($conflicts, $this->manifest18->getConflicts());
	}

	public function testElggPluginManifestGetActivateOnInstall() {
		$this->assertTrue($this->manifest18->getActivateOnInstall());
	}

	// \ElggPluginPackage
	public function testElggPluginPackageDetectIDFromPath() {
		$this->assertEquals('plugin_18', $this->package18->getID());
	}

	public function testElggPluginPackageDetectIDFromPluginID() {
		$package = new ElggPluginPackage('profile');
		$this->assertEquals('profile', $package->getID());
	}

	// \ElggPlugin
	public function testElggPluginIsValid() {
		$test_plugin = ElggPlugin::fromId('profile');
		$this->assertTrue($test_plugin->isValid());
	}

	public function testElggPluginGetID() {
		$test_plugin = ElggPlugin::fromId('profile');
		$this->assertEquals('profile', $test_plugin->getID());
	}

	public function testGetSettingRespectsDefaults() {
		$plugin = elgg_get_plugin_from_id('profile');
		if (!$plugin) {
			$this->markTestSkipped();
		}

		$cache = _elgg_services()->privateSettingsCache;
		$cache->save($plugin->guid, [
			__METHOD__ => 'foo',
		]);

		$this->assertEquals('foo', $plugin->getSetting(__METHOD__, 'bar'));
		$plugin->unsetSetting(__METHOD__);
		$this->assertEquals('bar', $plugin->getSetting(__METHOD__, 'bar'));
	}
}
