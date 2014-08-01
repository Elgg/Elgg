<?php

namespace Elgg\I18n;

class NullTranslatorTest extends \PHPUnit_Framework_TestCase {
	
	public function testAlwaysReturnsKeyAsTranslation() {
		$translator = new NullTranslator();
		$this->assertEquals('key', $translator->translate('key'));
	}
}