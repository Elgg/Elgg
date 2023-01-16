<?php

namespace Elgg\I18n;

use Elgg\Includer;
use Elgg\Project\Paths;
use Elgg\UnitTestCase;

class TranslationLoadingUnitTest extends UnitTestCase {

	/**
	 * Provides registered languages
	 *
	 * @return array
	 */
	public function languageProvider() {
		self::createApplication();

		_elgg_services()->translator->reloadAllTranslations();
		$languages = array_keys(_elgg_services()->translator->getLoadedTranslations());
		$provides = [];
		foreach ($languages as $lang) {
			$provides[] = [$lang];
		}

		return $provides;
	}
	
	public function coreLanguageProvider() {
		self::createApplication();
		
		$path = Paths::elgg() . 'languages/';
		if (!is_dir($path)) {
			// how??
			return [];
		}
		
		$provides = [];
		$codes = elgg()->locale->getLanguageCodes();
		$dh = new \DirectoryIterator($path);
		/* @var $file_info \DirectoryIterator */
		foreach ($dh as $file_info) {
			if (!$file_info->isFile() || $file_info->getExtension() !== 'php') {
				continue;
			}
			
			$language_code = $file_info->getBasename('.php');
			if (in_array($language_code, $codes)) {
				$provides[] = [$language_code];
			}
		}
		
		return $provides;
	}
	
	public function installationLanguageProvider() {
		self::createApplication();
		
		$path = Paths::elgg() . 'install/languages/';
		if (!is_dir($path)) {
			// how??
			return [];
		}
		
		$provides = [];
		$codes = elgg()->locale->getLanguageCodes();
		$dh = new \DirectoryIterator($path);
		/* @var $file_info \DirectoryIterator */
		foreach ($dh as $file_info) {
			if (!$file_info->isFile() || $file_info->getExtension() !== 'php') {
				continue;
			}
			
			$language_code = $file_info->getBasename('.php');
			if (in_array($language_code, $codes)) {
				$provides[] = [$language_code];
			}
		}
		
		return $provides;
	}

	/**
	 * @dataProvider languageProvider
	 */
	public function testCanLoadTranslations($language) {
		_elgg_services()->translator->setCurrentLanguage($language);

		_elgg_services()->translator->loadTranslations($language);

		$this->assertArrayHasKey($language, _elgg_services()->translator->getInstalledTranslations());

		$translations = _elgg_services()->translator->getLoadedTranslations();

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
		_elgg_services()->translator->setCurrentLanguage($language);

		_elgg_services()->translator->loadTranslations($language);

		$completeness = _elgg_services()->translator->getLanguageCompleteness($language);

		$translations = _elgg_services()->translator->getLoadedTranslations();

		if (!empty($translations[$language]) && $language !== 'en') {
			foreach ($translations[$language] as $key => $string) {
				if (empty($translations['en'][$key])) {
					// Translation exists for a string not found in 'en'
					unset($translations[$language][$key]);
					continue;
				}

				if ($string == $translations['en'][$key]) {
					// Translation is identical to 'en'
					unset($translations[$language][$key]);
					continue;
				}
			}
		}

		if (empty($translations[$language])) {
			$this->assertEmpty($completeness);
		} else {
			$this->assertGreaterThan(0, $completeness);
		}
	}
	
	/**
	 * Elgg uses Transifex, which sometimes produces language files with syntax errors
	 * We will try to catch those
	 *
	 * @dataProvider coreLanguageProvider
	 */
	public function testCanEncodeCoreTranslations($language) {
		$translations = Includer::includeFile(Paths::elgg() . "languages/{$language}.php");
		$this->assertIsArray($translations);
		// the JS translations are encoded to JSON, so try that
		$encoded = json_encode($translations);
		$this->assertNotEmpty($encoded);
		$this->assertIsString($encoded);
		
		$decoded = json_decode($encoded, true);
		$this->assertEquals($translations, $decoded);
	}
	
	/**
	 * Elgg uses Transifex, which sometimes produces language files with syntax errors
	 * We will try to catch those
	 *
	 * @dataProvider installationLanguageProvider
	 */
	public function testCanEncodeInstallationTranslations($language) {
		$translations = Includer::includeFile(Paths::elgg() . "install/languages/{$language}.php");
		$this->assertIsArray($translations);
		// the JS translations are encoded to JSON, so try that
		$encoded = json_encode($translations);
		$this->assertNotEmpty($encoded);
		$this->assertIsString($encoded);
		
		$decoded = json_decode($encoded, true);
		$this->assertEquals($translations, $decoded);
	}
}
