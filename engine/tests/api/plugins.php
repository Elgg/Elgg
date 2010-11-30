<?php
/**
 * Elgg Plugins Test
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCorePluginsAPITest extends ElggCoreUnitTest {
	var $manifest_file_18 = <<<___END
<?xml version="1.0" encoding="UTF-8"?>
<plugin_manifest version="1.8">
	<name>Test Manifest</name>
	<author>Anyone</author>
	<version>1.0</version>
	<blurb>A concise description.</blurb>
	<description>A longer, more interesting description.</description>
	<website>http://www.elgg.org/</website>
	<copyright>(C) Elgg 2010</copyright>
	<license>GNU Public License version 2</license>
	<depends>
		<type>elgg</type>
		<value>2009030802</value>
	</depends>

	<screenshot description="Fun things to do 1">graphics/plugin_ss1.png</screenshot>
	<screenshot description="Fun things to do 2">graphics/plugin_ss2.png</screenshot>

	<admin>
	<on_enable>setup_function</on_enable>
		<on_disable>teardown_function</on_disable>
		<interface_type>simple</interface_type>
	</admin>

	<depends>
		<type>php_extension</type>
		<value>gd</value>
	</depends>

	<depends>
		<type>php_ini</type>
		<name>safe_mode</name>
		<value>off</value>
	</depends>

	<conflicts>
		<type>plugin</type>
		<value>profile</value>
	</conflicts>

	<provides>
		<name>profile_api</name>
		<version>1.3</version>
	</provides>

</plugin_manifest>
___END;

	// 1.8 manifest object
	var $manifest18;

	var $manifest_file_17 = <<<___END
<?xml version="1.0" encoding="UTF-8"?>
<plugin_manifest>
	<field key="author" value="Anyone" />
	<field key="version" value="1.0" />
	<field key="description" value="A 1.7-style manifest" />
	<field key="website" value="http://www.elgg.org/" />
	<field key="copyright" value="(C) Elgg2008-2009" />
	<field key="license" value="GNU Public License version 2" />
	<field key="elgg_version" value="2009030702" />
</plugin_manifest>
___END;

	// 1.7 manifest object
	var $manifest17;

	public function __construct() {
		parent::__construct();

		$this->manifest18 = new ElggPluginManifest($this->manifest_file_18, 'unit_test');
		$this->manifest17 = new ElggPluginManifest($this->manifest_file_17);
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}


	// generic tests
	public function testElggPluginManifestFromString() {
		$manifest = new ElggPluginManifest($this->manifest_file_17);

		$this->assertIsA($manifest, 'ElggPluginManifest');
	}

	public function testElggPluginManifestFromFile() {
		$file = get_config('dataroot') . '/manifest_test.xml';
		$fp = fopen($file, 'wb');
		fputs($fp, $this->manifest_file_17);
		fclose($fp);

		$manifest = new ElggPluginManifest($file);

		$this->assertIsA($manifest, 'ElggPluginManifest');

		unlink($file);
	}

	public function testElggPluginManifestFromXML() {
		$xml = xml_to_object($this->manifest_file_17);
		$manifest = new ElggPluginManifest($xml);

		$this->assertIsA($manifest, 'ElggPluginManifest');
	}



	// 1.8 interface

	public function testElggPluginManifest18() {
		$manifest_array = array(
			'name' => 'Test Manifest',
			'author' => 'Anyone',
			'version' => '1.0',
			'blurb' => 'A concise description.',
			'description' => 'A longer, more interesting description.',
			'website' => 'http://www.elgg.org/',
			'copyright' => '(C) Elgg 2010',
			'license' => 'GNU Public License version 2',

			'depends' => array(
				array('type' => 'elgg', 'value' => '2009030802'),
				array('type' => 'php_extension', 'value' => 'gd'),
				array('type' => 'php_ini', 'name' => 'safe_mode', 'value' => 'off'),
			),

			'screenshots' => array(
				array('description' => 'Fun things to do 1', 'path' => 'graphics/plugin_ss1.png'),
				array('description' => 'Fun things to do 2', 'path' => 'graphics/plugin_ss2.png'),
			),

			'conflicts' => array(
				array('type' => 'plugin', 'value' => 'profile')
			),

			'provides' => array(
				array('name' => 'profile_api', 'version' => 1.3)
			),

			'admin' => array(
				'on_enable' => 'setup_function',
				'on_disable' => 'teardown_function',
				'interface_type' => 'simple'
			)
		);

		$this->assertEqual($this->manifest18->getManifest(), $manifest_array);
	}

	public function testElggPluginManifestGetApiVersion() {
		$this->assertEqual($this->manifest18->getApiVersion(), 1.8);
	}

	public function testElggPluginManifestGetName() {
		$this->assertEqual($this->manifest18->getName(), 'Test Manifest');
	}

	public function testElggPluginManifestGetAuthor() {
		$this->assertEqual($this->manifest18->getAuthor(), 'Anyone');
	}

	public function testElggPluginManifestGetVersion() {
		$this->assertEqual($this->manifest18->getVersion(), 1.0);
	}

	public function testElggPluginManifestGetBlurb() {
		$this->assertEqual($this->manifest18->getBlurb(), 'A concise description.');
	}

	public function testElggPluginManifestGetWebsite() {
		$this->assertEqual($this->manifest18->getWebsite(), 'http://www.elgg.org/');
	}

	public function testElggPluginManifestGetCopyright() {
		$this->assertEqual($this->manifest18->getCopyright(), '(C) Elgg 2010');
	}

	public function testElggPluginManifestGetLicense() {
		$this->assertEqual($this->manifest18->getLicense(), 'GNU Public License version 2');
	}


	// 1.7 interface

	public function testElggPluginManifest17() {
		$manifest_array = array(
			'author' => 'Anyone',
			'version' => '1.0',
			'description' => 'A 1.7-style manifest',
			'website' => 'http://www.elgg.org/',
			'copyright' => '(C) Elgg2008-2009',
			'license' => 'GNU Public License version 2',
			'elgg_version' => '2009030702'
		);

		$this->assertEqual($this->manifest17->getManifest(), $manifest_array);
	}

}
