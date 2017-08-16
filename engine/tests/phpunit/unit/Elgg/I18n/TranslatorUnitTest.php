<?php

namespace Elgg\I18n;

use Elgg\Logger;

/**
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
		$this->translator->addTranslation('en', [$this->key => 'Dummy']);
		$this->translator->addTranslation('es', [$this->key => 'Estúpido']);
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

		$this->assertEquals(2, count($logged));

		$this->assertEquals('Translations loaded from:', substr($logged[0]['message'], 0, 25));
		$this->assertEquals(Logger::INFO, $logged[0]['level']);

		$message = "Missing English translation for \"{$this->key}b\" language key";
		$this->assertEquals($message, $logged[1]['message']);
		$this->assertEquals(Logger::NOTICE, $logged[1]['level']);

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
}
