<?php

namespace Elgg\I18n;

/**
 * @group UnitTests
 */
class MessageTranslatorUnitTest extends \Elgg\UnitTestCase {

	public function up() {
		$this->english = Locale::parse('en');
		$this->spanish = Locale::parse('es');
	}

	public function down() {

	}

	public function testKeyIsReturnedIfNoTranslationCanBeFound() {
		$messages = new ArrayMessageBundle([]);
		$translator = new MessageTranslator(Locale::parse('en'), $messages);

		$this->assertEquals('foobar', $translator->translate('foobar'));
	}

	public function testTranslateReturnsTranslationForSpecifiedLocaleIfAvailable() {
		$messages = new ArrayMessageBundle([
			'en' => ['one' => 'one'],
			'es' => ['one' => 'uno'],
		]);
		$translator = new MessageTranslator(Locale::parse('en'), $messages);

		$this->assertEquals('uno', $translator->translate('one', [], Locale::parse('es')));
	}

	public function testTranslateReturnsTranslationForDefaultLocaleIfNoLocaleWasSpecified() {
		$messages = new ArrayMessageBundle([
			'en' => ['one' => 'one'],
			'es' => ['one' => 'uno'],
		]);
		$translator = new MessageTranslator(Locale::parse('en'), $messages);

		$this->assertEquals('one', $translator->translate('one', []));
	}

	public function testFallsBackToLanguageIfTranslationForSpecifiedLanguageIsNotAvailable() {
		$messages = new ArrayMessageBundle([
			'en' => ['one' => 'one'],
		]);
		$translator = new MessageTranslator(Locale::parse('en'), $messages);

		$this->assertEquals('one', $translator->translate('one', [], Locale::parse('es')));
	}

}
