<?php

namespace Elgg\I18n;

use Elgg\Logger;

class TranslatorTest extends \Elgg\TestCase {

	public $key = '__elgg_php_unit:test_key';

	/**
	 * @var Translator
	 */
	public $translator;

	public function setUp() {
		$this->translator = new Translator();
		$this->translator->addTranslation('en', [$this->key => 'Dummy']);
		$this->translator->addTranslation('es', [$this->key => 'Estúpido']);
	}

	public function testSetLanguageFromGetParameter() {
		_elgg_services()->input->set('hl', 'es');

		$this->assertEquals('es', $this->translator->detectLanguage());

		_elgg_services()->input->set('hl', null);
	}

	public function testCheckLanguageKeyExists() {
		$this->assertTrue($this->translator->languageKeyExists($this->key));
		$this->assertFalse($this->translator->languageKeyExists('__elgg_php_unit:test_key:missing'));
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
		$this->assertEquals('Dummy A', $this->translator->translate("{$this->key}a", [], 'es'));
	}

	public function testIssuesNoticeOnMissingKey() {
		// key is missing from all checked translations
		_elgg_services()->logger->disable();
		$this->assertEquals("{$this->key}b", $this->translator->translate("{$this->key}b"));
		$logged = _elgg_services()->logger->enable();

		$this->assertEquals([
			[
				'message' => "Missing English translation for \"{$this->key}b\" language key",
				'level' => Logger::NOTICE,
			]
				], $logged);

		// has fallback key
		$this->translator->addTranslation('en', ["{$this->key}b" => 'Dummy']);

		_elgg_services()->logger->disable();
		$this->assertEquals('Dummy', $this->translator->translate("{$this->key}b", [], 'es'));
		$logged = _elgg_services()->logger->enable();

		$this->assertEquals([
			[
				'message' => "Missing es translation for \"{$this->key}b\" language key",
				'level' => Logger::NOTICE,
			]
				], $logged);
	}

	public function testDoesNotProcessArgsOnKey() {
		$this->assertEquals('nonexistent:%s', $this->translator->translate('nonexistent:%s', [1]));
	}

}
