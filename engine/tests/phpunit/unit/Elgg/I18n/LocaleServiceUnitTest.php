<?php

namespace Elgg\I18n;

use Elgg\UnitTestCase;

/**
 * @group UnitTests
 * @group I18n
 */
class LocaleServiceUnitTest extends UnitTestCase{
	
	/**
	 * @var LocaleService
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		$this->service = elgg()->locale;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		unset($this->service);
	}
	
	public function testConstructorWithCustomLocales() {
		$config = elgg()->config;
		
		$config->language_to_locale_mapping = [
			'en' => ['en_US'],
			'nl' => ['nl_NL'],
		];
		
		$service = new LocaleService($config);
		
		$this->assertEquals(['en_US'], $service->getLocaleForLanguage('en'));
		$this->assertEquals(['nl_NL'], $service->getLocaleForLanguage('nl'));
		$this->assertEquals([], $service->getLocaleForLanguage('de'));
	}
	
	public function testConstructorWithCustomLanguages() {
		$config = elgg()->config;
		
		$config->language_to_locale_mapping = [
			'my_language' => ['en_US'],
		];
		
		$service = new LocaleService($config);
		
		$this->assertContains('my_language', $service->getLanguageCodes());
		$this->assertEquals(['en_US'], $service->getLocaleForLanguage('my_language'));
		$this->assertEquals([], $service->getLocaleForLanguage('de'));
	}
	
	public function testGetLanguageCodes() {
		$this->assertInternalType('array', $this->service->getLanguageCodes());
	}
	
	public function testGetLocaleForInvalidLanguage() {
		$this->assertEquals([], $this->service->getLocaleForLanguage('invalid'));
	}
	
	public function testSetLocaleForLanguage() {
		$language = 'en';
		$other_language = 'nl';
		$locale = ['en_US', 'en_UK'];
		
		$this->assertEquals([], $this->service->getLocaleForLanguage($language));
		$this->assertEquals([], $this->service->getLocaleForLanguage($other_language));
		
		$this->service->setLocaleForLanguage($language, $locale);
		
		$this->assertEquals($locale, $this->service->getLocaleForLanguage($language));
		$this->assertEquals([], $this->service->getLocaleForLanguage($other_language));
	}
	
	public function testGetLocale() {
		$expected = (array) setlocale(LC_ALL, 0);
		$actual = $this->service->getLocale(LC_ALL);
		
		$this->assertInternalType('array', $actual);
		$this->assertEquals($expected, $actual);
	}
	
	public function testSetInvalidLocale() {
		$current = (array) setlocale(LC_ALL, 0);
		$this->assertEquals($current, $this->service->setLocale(LC_ALL, 'invalid'));
		$this->assertNotEquals('invalid', setlocale(LC_ALL, 0));
	}
	
	public function testSetLocale() {
		$this->markTestSkipped('No reliable way to test setlocale as it depends on the test system');
	}
	
	public function testSetLocaleFromLanguageKey() {
		$this->markTestSkipped('No reliable way to test setlocale as it depends on the test system');
	}
}
