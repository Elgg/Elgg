<?php

namespace Elgg\Plugins;

use Elgg\I18n\Translator;

abstract class TranslatorUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var Translator
	 */
	public $translator;

	public function up() {
		$this->translator = new Translator(_elgg_config());
		$this->translator->loadTranslations('en');
		$this->translator->registerTranslations($this->getPath(), false, 'en');

		_elgg_services()->setValue('translator', $this->translator);
	}

	public function down() {

	}

	/**
	 * Returns path to language directory
	 * @return string
	 */
	public function getPath() {
		$reflector = new \ReflectionObject($this);
		$fn = $reflector->getFileName();

		$path = sanitise_filepath(dirname($fn));
		$plugins_path = sanitise_filepath(elgg_get_plugins_path());

		if (strpos($path, $plugins_path) === 0) {
			$relative_path = substr($path, strlen($plugins_path));
			list($plugin_id, $filepath) = explode('/', $relative_path, 2);
			return $plugins_path . $plugin_id . '/languages/';
		}

		return '';
	}

	/**
	 * Provides registered languages
	 * @return array
	 */
	public function languageProvider() {
		$provides = [];

		$codes = Translator::getAllLanguageCodes();

		foreach ($codes as $code) {
			if (file_exists(rtrim($this->getPath(), '/') . "/$code.php")) {
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

		$this->translator->registerTranslations($this->getPath(), false, $language);

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

		$this->translator->registerTranslations($this->getPath(), false, $language);

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