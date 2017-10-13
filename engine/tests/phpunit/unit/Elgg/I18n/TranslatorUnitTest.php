<?php

namespace Elgg\I18n;

use Elgg\Logger;
use Elgg\Project\Paths;

/**
 * @group Translator
 * @group UnitTests
 */
class TranslatorUnitTest extends \Elgg\UnitTestCase {

	public $key = '__elgg_php_unit:test_key';

	/**
	 * @var Translator
	 */
	public $translator;

	public function up() {
		$this->translator = new Translator(_elgg_config());
		$this->translator->loadTranslations('en');

		$this->translator->addTranslation('en', [$this->key => 'Dummy']);
		$this->translator->addTranslation('es', [$this->key => 'Estúpido']);

		_elgg_services()->setValue('translator', $this->translator);
	}

	public function down() {

	}

	public function testSetLanguageFromGetParameter() {
		_elgg_services()->input->set('hl', 'es');

		$this->assertEquals('es', $this->translator->detectLanguage());

		_elgg_services()->input->set('hl', null);
	}

	public function testCheckLanguageKeyExists() {
		$this->assertTrue($this->translator->languageKeyExists($this->key));

		_elgg_services()->logger->disable();
		$this->assertFalse($this->translator->languageKeyExists('__elgg_php_unit:test_key:missing'));
		_elgg_services()->logger->enable();
	}

	public function testCanTranslate() {
		$this->assertEquals('Dummy', $this->translator->translate($this->key));
		$this->assertEquals('Estúpido', $this->translator->translate($this->key, [], 'es'));
	}

	public function testUsesSprintfArguments() {
		$this->translator->addTranslation('en', [$this->key => 'Dummy %s']);
		$this->assertEquals('Dummy %s', $this->translator->translate($this->key));
		$this->assertEquals('Dummy 1', $this->translator->translate($this->key, [1]));

		$this->translator->addTranslation('en', [$this->key => 'Dummy %2$s %1$s']);
		$this->assertEquals('Dummy 2 1', $this->translator->translate($this->key, [1, 2]));
	}

	public function testFallsBackToEnglish() {
		$this->translator->addTranslation('en', ["{$this->key}a" => 'Dummy A']);
		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy A', $this->translator->translate("{$this->key}a", [], 'es'));
		_elgg_services()->logger->enable();
	}

	public function testIssuesNoticeOnMissingKey() {
		// key is missing from all checked translations
		$logger = _elgg_services()->logger;
		$logger->disable();

		$this->assertEquals("{$this->key}b", $this->translator->translate("{$this->key}b"));
		$logged = $logger->enable();

		$this->assertEquals(1, count($logged));

		$message = "Missing English translation for \"{$this->key}b\" language key";
		$this->assertEquals($message, $logged[0]['message']);
		$this->assertEquals(Logger::NOTICE, $logged[0]['level']);

		// has fallback key
		$this->translator->addTranslation('en', ["{$this->key}b" => 'Dummy']);

		$logger->disable();
		$this->assertEquals('Dummy', $this->translator->translate("{$this->key}b", [], 'es'));
		$logged = $logger->enable();

		$this->assertEquals([
			[
				'message' => "Missing es translation for \"{$this->key}b\" language key",
				'level' => Logger::NOTICE,
			]
				], $logged);
	}

	public function testDoesNotProcessArgsOnKey() {
		_elgg_services()->logger->disable();
		$this->assertEquals('nonexistent:%s', $this->translator->translate('nonexistent:%s', [1]));
		_elgg_services()->logger->enable();
	}

	/**
	 * Provides registered languages
	 * @return array
	 */
	public function languageProvider() {
		$provides = [];

		$codes = Translator::getAllLanguageCodes();

		foreach ($codes as $code) {
			if (file_exists(Paths::elgg() . "languages/$code.php")) {
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

		$this->translator->loadTranslations($language);

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

		$this->translator->loadTranslations($language);

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

	public function testCanSetCurrentLanguage() {
		$this->translator->setCurrentLanguage(null);
		$this->assertEquals('en', $this->translator->getCurrentLanguage());

		$this->translator->setCurrentLanguage('lang');
		$this->assertEquals('lang', $this->translator->getCurrentLanguage());
	}

	public function testCanDetectCurrentLanguageFromInput() {
		$this->translator->setCurrentLanguage(null);
		_elgg_services()->input->set('hl', 'aa');
		$this->assertEquals('aa', $this->translator->getCurrentLanguage());
	}

	public function testCanDetectCurrentLanguageFromUserSettings() {

		$this->translator->setCurrentLanguage(null);

		$language = 'ab';

		$ia = elgg_set_ignore_access(true);
		$user = $this->createUser([], [
			'language' => $language,
		]);

		elgg_set_ignore_access($ia);

		$this->assertEquals('en', $this->translator->getCurrentLanguage());

		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals($language, $this->translator->getCurrentLanguage());

		_elgg_services()->session->removeLoggedInUser();

		$user->delete();
	}

	public function testCanDetectCurrentLanguageFromConfig() {
		$this->translator->setCurrentLanguage(null);
		elgg_set_config('language', 'af');
		$this->assertEquals('af', $this->translator->getCurrentLanguage());
	}

}
