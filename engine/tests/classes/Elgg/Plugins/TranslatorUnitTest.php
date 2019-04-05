<?php

namespace Elgg\Plugins;

use Elgg\I18n\Translator;

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

	public function down() {

	}

	/**
	 * Provides registered languages
	 * @return array
	 */
	public function languageProvider() {
		self::createApplication();

		$provides = [];

		$codes = elgg()->locale->getLanguageCodes();

		$path = $this->getPath();
		if (!$path) {
			return $provides;
		}

		foreach ($codes as $code) {
			if (file_exists($path . "/languages/$code.php")) {
				$provides[] = [$code];
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

		$completeness = get_language_completeness($language);

		$translations = $this->translator->getLoadedTranslations();

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