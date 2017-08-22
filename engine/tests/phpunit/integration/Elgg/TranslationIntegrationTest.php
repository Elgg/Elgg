<?php
/**
 *
 */

namespace Elgg;

use Elgg\I18n\Translator;

/**
 * @group IntegrationTests
 * @group Translator
 */
class TranslationIntegrationTest extends IntegrationTestCase {

	private $translations;

	public function up() {
		$this->translations = _elgg_services()->translator->getLoadedTranslations();
	}

	public function down() {

	}

	/**
	 * Elgg uses Transifex, which sometimes produces language files with syntax errors
	 * We will try to catch those
	 *
	 * @dataProvider languageProvider
	 */
	public function testCanLoadTranslations($language) {

		_elgg_services()->translator->setCurrentLanguage($language);

		$en = count($this->translations['en']);

		$completeness = get_language_completeness($language);

		$missing = _elgg_services()->translator->getMissingLanguageKeys($language);
		if ($missing) {
			$missing = count($missing);
		} else {
			$missing = 0;
		}

		$complete = $en - $missing;

		$this->assertEquals($completeness, round(($complete / $en) * 100, 2));

		if ($completeness) {
			// We don't really need to iterate through all translations
			$key = array_rand($this->translations[$language]);
			$string = $this->translations[$language][$key];

			$this->assertTrue(elgg_language_key_exists($key, $language));
			if (!$string) {
				$string = $this->translations['en'];
			}

			$this->assertEquals($string, elgg_echo($key, [], $language));
			$this->assertEquals($string, elgg_echo($key, []));
		}
	}

	/**
	 * @group TranslatorDetection
	 */
	public function testCanDetectCurrentLanguageFromInput() {
		_elgg_services()->translator->setCurrentLanguage(null);
		_elgg_services()->input->set('hl', 'aa');
		$this->assertEquals('aa', _elgg_services()->translator->getCurrentLanguage());
	}

	/**
	 * @group TranslatorDetection
	 */
	public function testCanDetectCurrentLanguageFromUserSettings() {
		$this->markTestSkipped("For whatever reason there is no user in session when called from Translator");

		_elgg_services()->translator->setCurrentLanguage(null);

		$ia = elgg_set_ignore_access(true);
		$user = $this->createUser();
		$user->language = 'ab';
		$user->save();
		elgg_set_ignore_access($ia);

		$this->assertEquals('en', _elgg_services()->translator->getCurrentLanguage());

		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals('ab', _elgg_services()->translator->getCurrentLanguage());

		_elgg_services()->session->removeLoggedInUser();

		$user->delete();
	}

	/**
	 * @group TranslatorDetection
	 */
	public function testCanDetectCurrentLanguageFromConfig() {
		_elgg_services()->translator->setCurrentLanguage(null);
		elgg_set_config('language', 'af');
		$this->assertEquals('af', _elgg_services()->translator->getCurrentLanguage());
	}

	public function languageProvider() {
		$provides = [];
		$codes = Translator::getAllLanguageCodes();
		foreach ($codes as $code) {
			$provides[] = [$code];
		}

		return $provides;
	}
}