<?php

class Elgg_I18n_TranslationLoaderTest extends PHPUnit_Framework_TestCase {

	/** @var string */
	protected $coreDir;

	/** @var string */
	protected $pluginDir;

	protected function setUp() {
		$this->coreDir = dirname(dirname(dirname(__FILE__))) . '/test_files/languages/core/';
		$this->pluginDir = dirname(dirname(dirname(__FILE__))) . '/test_files/languages/plugin/';
	}

	public function testAddDirectory() {
		$loader = new Elgg_I18n_TranslationLoader();
		$this->assertEquals(array(), $loader->getDirectories());
		$loader->addDirectory('/tmp/');
		$this->assertEquals(array('/tmp'), $loader->getDirectories());
		$loader->addDirectory('C:\\elgg\\');
		$this->assertEquals(array('/tmp', 'C:\\elgg'), $loader->getDirectories());
	}

	public function testGetAllLanguages() {
		$loader = new Elgg_I18n_TranslationLoader();
		$loader->addDirectory($this->pluginDir);
		$this->assertEquals(array('en'), $loader->getAllLanguages());
		$loader->addDirectory($this->coreDir);
		$expected = array('en', 'es', 'fr');
		$languages = $loader->getAllLanguages();
		$this->assertEquals(sort($expected), sort($languages));
	}

	public function testLoadTranslationIncrementally() {
		$loader = new Elgg_I18n_TranslationLoader();
		$loader->addDirectory($this->coreDir);
		$expected = array(
			'n1' => 'one',
			'n2' => 'two',
			'n3' => 'three',
			'greeting' => 'Hello, %s',
			'unique' => 'not in Spanish',
		);
		$this->assertEquals($expected, $loader->loadTranslation('en'));
		$loader->addDirectory($this->pluginDir);
		$expected['greeting'] = 'Hey, %s';
		$expected['empty'] = 'Set by plugin';
		$this->assertEquals($expected, $loader->loadTranslation('en'));
	}

	public function testLoadTranslationFromExistingCache() {
		$cache = new ElggStaticVariableCache('phpunit');
		$cache->setVariable('en.lang', serialize(array('test' => 'foo')));
		$loader = new Elgg_I18n_TranslationLoader($cache);
		$loader->addDirectory($this->coreDir);
		$this->assertEquals(array('test' => 'foo'), $loader->loadTranslation('en'));
	}

	public function testCacheSetting() {
		$cache = new ElggStaticVariableCache('phpunit');
		$loader = new Elgg_I18n_TranslationLoader($cache);
		$loader->addDirectory($this->coreDir);
		$loader->loadTranslation('en');
		unset($loader);
		$expected = array(
			'n1' => 'one',
			'n2' => 'two',
			'n3' => 'three',
			'greeting' => 'Hello, %s',
			'unique' => 'not in Spanish',
		);
		$this->assertEquals($expected, unserialize($cache->getVariable('en.lang')));
	}
}
