<?php

namespace Elgg\I18n;

use Elgg\IntegratedUnitTestCase;

class TranslationLoadingUnitTestCase extends IntegratedUnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * Provides registered languages
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
			$this->assertTrue($completeness == 0);
		} else {
			$this->assertTrue($completeness > 0);
		}
	}

}
