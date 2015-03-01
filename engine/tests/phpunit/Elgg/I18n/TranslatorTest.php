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
	
}