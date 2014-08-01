<?php
namespace Elgg\I18n;

use Elgg\Filesystem\Filesystem;

class DefaultTranslatorTest extends \PHPUnit_Framework_TestCase {

	/** @var string */
	private $coreDir;

	/** @var string */
	private $mockPluginDir;

	protected function setUp() {
		global $TEST_FILES;
		
		$this->filesystem = Filesystem::createLocal("$TEST_FILES/languages/");
		
		$this->coreDir = $this->filesystem->chroot('core');
		$this->pluginDir = $this->filesystem->chroot('plugin');
		
		$this->loader = new Loader();
		$this->loader->addDirectory($this->coreDir);

		$this->logger = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		
		$this->translator = new DefaultTranslator($this->loader, $this->logger);
	}

	public function testKeyIsReturnedIfNoTranslationCanBeFound() {
		$this->translator->setUserLocale(Locale::parse('es'));
		$this->assertEquals('empty', $this->translator->translate('empty'));
	}

	public function testTranslateReturnsTranslationForSpecifiedLanguageIfAvailable() {
		$expectedTranslation = 'uno';
		
		$actualTranslation = $this->translator->translate('n1', array(), Locale::parse('es'));
		
		$this->assertEquals($expectedTranslation, $actualTranslation);
		
		// TODO: Remove in 2.0 when globals are banished
		_elgg_services()->setValue('translator', $this->translator);
		
		$actualTranslation = elgg_echo('n1', array(), 'es');
		
		$this->assertEquals($expectedTranslation, $actualTranslation);
	}
	
	public function testTranslateReturnsTranslationForUserLanguageIfNoLanguageWasSpecified() {
		
	}

	public function testFallsBackToUserLanguageIfTranslationForSpecifiedLanguageIsNotAvailable() {
		
	}
	
	public function testTranslateWithCacheOffAndUserLanguage() {
		$this->translator->setUserLocale(Locale::parse('es'));
		$this->assertEquals('uno', $this->translator->translate('n1'));
		$this->assertEquals('Hola, Tom', $this->translator->translate('greeting', array('Tom')));
	}

	public function testTranslateWithCacheOffAndSiteLanguage() {
		$this->translator->setUserLocale(Locale::parse('es'));
		$this->assertEquals('not in Spanish', $this->translator->translate('unique'));
	}

	public function testTranslateWithCacheOffAndLanguageOverride() {
		$this->translator->setUserLocale(Locale::parse('es'));
		$this->assertEquals('un', $this->translator->translate('n1', array(), Locale::parse('fr')));
		$this->assertEquals('Bonjour, Tom', $this->translator->translate('greeting', array('Tom'), Locale::parse('fr')));
	}

	public function testTranslateWithCacheOffAndPluginsLoaded() {
		$this->loader->addDirectory($this->pluginDir);
		$this->translator->setUserLocale(Locale::parse('en'));
		$this->assertEquals('one', $this->translator->translate('n1'));
		$this->assertEquals('Hey, Tom', $this->translator->translate('greeting', array('Tom')));
		$this->assertEquals('Set by plugin', $this->translator->translate('empty'));
	}

	public function testTranslateWithCacheOffAndPluginsLoadedAfterUse() {
		$this->translator->setUserLocale(Locale::parse('en'));
		$this->assertEquals('Hello, Tom', $this->translator->translate('greeting', array('Tom')));
		$this->loader->addDirectory($this->pluginDir);
		$this->assertEquals('Hey, Tom', $this->translator->translate('greeting', array('Tom')));
	}
}
