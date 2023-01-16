<?php

namespace Elgg\Plugins;

use Elgg\I18n\Translator;
use Elgg\Includer;

abstract class TranslatorUnitTest extends \Elgg\UnitTestCase {

	use PluginTesting;

	/**
	 * @var Translator
	 */
	public $translator;

	public function up() {
		$this->translator = _elgg_services()->translator;
		$this->translator->reloadAllTranslations();
	}

	/**
	 * Provides registered languages
	 *
	 * @return array
	 */
	public function languageProvider() {
		self::createApplication();
		
		$path = $this->getPath();
		if (!$path || !is_dir("{$path}/languages/")) {
			return [];
		}
		
		$provides = [];
		$codes = elgg()->locale->getLanguageCodes();
		$dh = new \DirectoryIterator("{$path}/languages/");
		/* @var $file_info \DirectoryIterator */
		foreach ($dh as $file_info) {
			if (!$file_info->isFile() || $file_info->getExtension() !== 'php') {
				continue;
			}
			
			$plugin_language_code = $file_info->getBasename('.php');
			if (in_array($plugin_language_code, $codes)) {
				$provides[] = [$plugin_language_code];
			}
		}

		return $provides;
	}

	/**
	 * @dataProvider languageProvider
	 */
	public function testCanLoadTranslations($language) {
		$this->translator->setCurrentLanguage($language);

		$this->translator->registerTranslations($this->getPath() . 'languages/', false, $language);

		$this->assertArrayHasKey($language, $this->translator->getInstalledTranslations());

		$translations = $this->translator->getLoadedTranslations();

		// We don't really need to iterate through all translations
		$key = array_rand($translations[$language]);
		$string = $translations[$language][$key];

		$this->assertTrue(elgg_language_key_exists($key, $language));
		$this->assertEquals($string, elgg_echo($key, [], $language));
		$this->assertEquals($string, elgg_echo($key, []));
	}

	/**
	 * Elgg uses Transifex, which sometimes produces language files with syntax errors
	 * We will try to catch those
	 *
	 * @dataProvider languageProvider
	 */
	public function testCanCalculateLanguageCompleteness($language) {
		$this->translator->setCurrentLanguage($language);

		$this->translator->registerTranslations($this->getPath() . 'languages/', false, $language);

		$completeness = $this->translator->getLanguageCompleteness($language);

		$translations = $this->translator->getLoadedTranslations();

		if (!empty($translations[$language]) && $language !== 'en') {
			foreach ($translations[$language] as $key => $string) {
				if (empty($translations['en'][$key])) {
					// Translation exists for a string not found in 'en'
					unset($translations[$language][$key]);
					continue;
				}

				if ($string === $translations['en'][$key]) {
					// Translation is identical to 'en'
					unset($translations[$language][$key]);
					continue;
				}
			}
		}

		if (empty($translations[$language])) {
			$this->assertEquals(0, $completeness);
		} else {
			$this->assertGreaterThan(0, $completeness);
		}
	}
	
	/**
	 * Elgg uses Transifex, which sometimes produces language files with syntax errors
	 * We will try to catch those
	 *
	 * @dataProvider languageProvider
	 */
	public function testCanEncodeTranslations($language) {
		$translations = Includer::includeFile("{$this->getPath()}/languages/{$language}.php");
		$this->assertIsArray($translations);
		// the JS translations are encoded to JSON, so try that
		$encoded = json_encode($translations);
		$this->assertNotEmpty($encoded);
		$this->assertIsString($encoded);
		
		$decoded = json_decode($encoded, true);
		$this->assertEquals($translations, $decoded);
	}
}
