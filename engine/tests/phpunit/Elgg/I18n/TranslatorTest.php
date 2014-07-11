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

	/**
	 * @expectedException Elgg_I18n_InvalidLanguageException
	 */
	public function testConstructorWithInvalidLanguage() {
		$this->translator = new Elgg_I18n_Translator('english', array());
	}

	public function testGetWithNoArgs() {
		$this->assertEquals($this->translation['no_args'], $this->translator->get('no_args'));
	}

	public function testGetWithArgs() {
		$ans = 'word here and 45';
		$this->assertEquals($ans, $this->translator->get('with_args', array('here', 45)));
	}

	public function testGetWithNonExistentKey() {
		$this->assertFalse($this->translator->get('non-existent'));
	}

	public function testGetTranslation() {
		$this->assertEquals($this->translation, $this->translator->getTranslation());
	}

	public function testSetTranslationWithReplace() {
		$this->translator->setTranslation(array('foo' => 'test'), true);
		$this->assertEquals(array('foo' => 'test'), $this->translator->getTranslation());
	}

	public function testSetTranslationWithoutReplace() {
		$this->translator->setTranslation(array('no_args' => 'new', 'foo' => 'test'));
		$expected = $this->translation;
		$expected['no_args'] = 'new';
		$expected['foo'] = 'test';
		$this->assertEquals($expected, $this->translator->getTranslation());
	}
}
