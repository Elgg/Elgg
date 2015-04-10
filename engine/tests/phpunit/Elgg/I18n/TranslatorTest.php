<?php
namespace Elgg\I18n;

use PHPUnit_Framework_TestCase as TestCase;

class TranslatorTest extends TestCase {
	
	public function testSetLanguageFromGetParameter() {
		$translator = new Translator();
		
		$input_lang = 'nl';
		_elgg_services()->input->set('hl', $input_lang);
		
		$lang = $translator->getLanguage();
		$this->assertEquals($lang, $input_lang);
	}
	
	public function testCheckLanguageKeyExists() {
		$translator = new Translator();
		
		$translator->addTranslation('en', array('__elgg_php_unit:test_key' => 'Dummy'));
		
		$this->assertTrue($translator->languageKeyExists('__elgg_php_unit:test_key'));
		$this->assertFalse($translator->languageKeyExists('__elgg_php_unit:test_key:missing'));
	}
	
	public function testDoesNotPerformSprintfFormattingIfArgsNotProvided() {
		$this->markTestIncomplete();
	}
}