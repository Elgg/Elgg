<?php

class Elgg_I18n_TranslationsServiceTest extends PHPUnit_Framework_TestCase {

	/** @var string */
	protected $coreDir;

	/** @var string */
	protected $pluginDir;

	protected function setUp() {
		$this->coreDir = dirname(dirname(dirname(__FILE__))) . '/test_files/languages/core/';
		$this->pluginDir = dirname(dirname(dirname(__FILE__))) . '/test_files/languages/plugin/';
	}

	/**
	 * @return Elgg_I18n_TranslationLoader
	 */
	protected function getLoaderWithoutCache() {
		return new Elgg_I18n_TranslationLoader();
	}

	/**
	 * @return Elgg_I18n_TranslationsService
	 */
	protected function getServiceWithoutCache() {
		$loader = $this->getLoaderWithoutCache();
		return new Elgg_I18n_TranslationsService($loader);
	}

	/**
	 * @expectedException Elgg_I18n_InvalidLanguageException
	 */
	public function testConstructorWithInvalidSiteLanguage() {
		$loader = $this->getLoaderWithoutCache();
		new Elgg_I18n_TranslationsService($loader, 'english');
	}

	/**
	 * @expectedException Elgg_I18n_InvalidLanguageException
	 */
	public function testConstructorWithInvalidUserLanguage() {
		$loader = $this->getLoaderWithoutCache();
		new Elgg_I18n_TranslationsService($loader, 'es', 'spanish');
	}

	public function testConstructorWithValidArguments() {
		$loader = $this->getLoaderWithoutCache();
		$service = new Elgg_I18n_TranslationsService($loader, 'en', 'es');
		$this->assertEquals('en', $service->getSiteLanguage());
		$this->assertEquals('es', $service->getUserLanguage());
	}

	public function testSetUserLanguageWithValidLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->setUserLanguage('fr');
		$this->assertEquals('fr', $service->getUserLanguage());
	}

	/**
	 * @expectedException Elgg_I18n_InvalidLanguageException
	 */
	public function testSetUserLanguageWithInvalidLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->setUserLanguage('french');
	}

	public function testSetSiteLanguageWithValidLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->setSiteLanguage('fr');
		$this->assertEquals('fr', $service->getSiteLanguage());
	}

	/**
	 * @expectedException Elgg_I18n_InvalidLanguageException
	 */
	public function testSetSiteLanguageWithInvalidLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->setSiteLanguage('french');
	}

	public function testRegisterTranslationDirectory() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory('/tmp/');
		$service->registerTranslationDirectory('C:\\elgg');
		$this->assertEquals(array('/tmp', 'C:\\elgg'), $service->getTranslationDirectories());
	}

	public function testTranslateWithCacheOffAndUserLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->setUserLanguage('es');
		$this->assertEquals('uno', $service->translate('n1'));
		$this->assertEquals('Hola, Tom', $service->translate('greeting', array('Tom')));
	}

	public function testTranslateWithCacheOffAndSiteLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->setUserLanguage('es');
		$this->assertEquals('not in Spanish', $service->translate('unique'));
	}

	public function testTranslateWithCacheOffAndMissingKey() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->setUserLanguage('es');
		$this->assertEquals('empty', $service->translate('empty'));
	}

	public function testTranslateWithCacheOffAndLanguageOverride() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->setUserLanguage('es');
		$this->assertEquals('un', $service->translate('n1', array(), 'fr'));
		$this->assertEquals('Bonjour, Tom', $service->translate('greeting', array('Tom'), 'fr'));
	}

	public function testTranslateWithCacheOffAndPluginsLoaded() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->registerTranslationDirectory($this->pluginDir);
		$service->setUserLanguage('en');
		$this->assertEquals('one', $service->translate('n1'));
		$this->assertEquals('Hey, Tom', $service->translate('greeting', array('Tom')));
		$this->assertEquals('Set by plugin', $service->translate('empty'));
	}

	public function testTranslateWithCacheOffAndPluginsLoadedAfterUse() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->setUserLanguage('en');
		$this->assertEquals('Hello, Tom', $service->translate('greeting', array('Tom')));
		$service->registerTranslationDirectory($this->pluginDir);
		$this->assertEquals('Hey, Tom', $service->translate('greeting', array('Tom')));
	}

	public function testTranslateOffAndOn() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$service->turnOff();
		$this->assertEquals('n1', $service->translate('n1'));
		$this->assertEquals('greeting', $service->translate('greeting', array('Tom')));
		$service->turnOn();
		$this->assertEquals('one', $service->translate('n1'));
		$this->assertEquals('Hello, Tom', $service->translate('greeting', array('Tom')));
	}

	public function testSetTranslator() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$this->assertEquals('one', $service->translate('n1'));

		$translator = new Elgg_I18n_Translator('en', array('n1' => 'not one'));
		$service->setTranslator($translator);
		$this->assertEquals('not one', $service->translate('n1'));
	}

	public function testGetTranslatorForUndefinedLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$translator = $service->getTranslator('ru');
		$this->assertEquals(array(), $translator->getTranslationAsArray());
	}

	public function testGetTranslatorForDefinedLanguage() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$translator = $service->getTranslator('en');
		$expected = array(
			'n1' => 'one',
			'n2' => 'two',
			'n3' => 'three',
			'greeting' => 'Hello, %s',
			'unique' => 'not in Spanish',
		);
		$this->assertEquals($expected, $translator->getTranslationAsArray());
	}

	public function testGetAllTranslators() {
		$service = $this->getServiceWithoutCache();
		$service->registerTranslationDirectory($this->coreDir);
		$translators = $service->getAllTranslators();
		$this->assertEquals(3, count($translators));
		$this->assertEquals('one', $translators['en']->get('n1'));
		$this->assertEquals('uno', $translators['es']->get('n1'));
		$this->assertEquals('un', $translators['fr']->get('n1'));
	}
}
