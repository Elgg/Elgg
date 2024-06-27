<?php

namespace Elgg\Plugins;

use Elgg\I18n\Translator;
use Elgg\Includer;
use Elgg\PluginsIntegrationTestCase;

class TranslationsIntegrationTest extends PluginsIntegrationTestCase {
	
	/**
	 * @var Translator
	 */
	protected $translator;
	
	public function up() {
		parent::up();
		
		_elgg_services()->reset('translator');
		
		$this->translator = _elgg_services()->translator;
	}
	
	/**
	 * Provides registered languages per plugin
	 *
	 * @return array
	 */
	public static function languageProvider(): array {
		self::createApplication([
			'isolate' => true,
		]);
		
		$result = [];
		
		$codes = elgg()->locale->getLanguageCodes();
		$plugins = elgg_get_plugins();
		foreach ($plugins as $plugin) {
			$languages_path = $plugin->getPath() . 'languages/';
			if (!is_dir($languages_path)) {
				continue;
			}
			
			$dh = new \DirectoryIterator($languages_path);
			/* @var $file_info \DirectoryIterator */
			foreach ($dh as $file_info) {
				if (!$file_info->isFile() || $file_info->getExtension() !== 'php') {
					continue;
				}
				
				$plugin_language_code = $file_info->getBasename('.php');
				if (in_array($plugin_language_code, $codes)) {
					$result[] = [$plugin, $plugin_language_code, $plugin->getID()];
				}
			}
		}
		
		if (empty($result)) {
			// hack so test can check if there are no translations provided
			$result[] = [null, null];
		}
		
		return $result;
	}
	
	/**
	 * @dataProvider languageProvider
	 */
	public function testCanLoadTranslations(?\ElggPlugin $plugin, ?string $language) {
		if (!isset($plugin)) {
			$this->markTestSkipped('no plugin translations to test');
		}
		
		$this->translator->setCurrentLanguage($language);
		
		$this->assertTrue($this->translator->registerTranslations($plugin->getPath() . 'languages/', false, $language));
		
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
	public function testCanCalculateLanguageCompleteness(?\ElggPlugin $plugin, ?string $language) {
		if (!isset($plugin)) {
			$this->markTestSkipped('no plugin translations to test');
		}
		
		$this->translator->setCurrentLanguage($language);
		
		$this->translator->registerTranslations($plugin->getPath() . 'languages/', false, $language);
		
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
	public function testCanEncodeTranslations(?\ElggPlugin $plugin, ?string $language) {
		if (!isset($plugin)) {
			$this->markTestSkipped('no plugin translations to test');
		}
		
		$translations = Includer::includeFile("{$plugin->getPath()}/languages/{$language}.php");
		
		$this->assertIsArray($translations);
		
		// the JS translations are encoded to JSON, so try that
		$encoded = json_encode($translations);
		$this->assertNotEmpty($encoded);
		$this->assertIsString($encoded);
		
		$decoded = json_decode($encoded, true);
		$this->assertEquals($translations, $decoded);
	}
}
