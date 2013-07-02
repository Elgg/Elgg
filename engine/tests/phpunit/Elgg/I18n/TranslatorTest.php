<?php

class Elgg_I18n_TranslatorTest extends PHPUnit_Framework_TestCase {

	/** @var Elgg_I18n_Translator */
	protected $translator;

	/** @var array */
	protected $translation;

	protected function setUp() {
		$this->translation = array(
			'no_args' => 'test',
			'with_args' => 'word %s and %u',
		);
		$this->translator = new Elgg_I18n_Translator('en', $this->translation);
	}

	public function testNoArgs() {
		$this->assertEquals($this->translation['no_args'], $this->translator->translate('no_args'));
	}

	public function testWithArgs() {
		$ans = 'word here and 45';
		$this->assertEquals($ans, $this->translator->translate('with_args', array('here', 45)));		
	}

	public function testNonExistentKey() {
		$this->assertFalse($this->translator->translate('non-existent'));
	}
}
