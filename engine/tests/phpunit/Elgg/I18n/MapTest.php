<?php

namespace Elgg\I18n;

class MapTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->map = new Map();
	}
	
	public function testGetReturnsTheValueForTheGivenKey() {
		$this->map->addTranslations(array('foo' => 'bar'));
		
		$this->assertEquals('bar', $this->map->get('foo'));
	}

	public function testGetNonExistentKeyReturnsNull() {
		$this->assertNull($this->map->get('non-existent'));
	}

	public function testGetPutsArgsIntoSprintfPlaceholders() {
		$this->map->addTranslations(array('foo' => 'word %s and %u'));
		
		$this->assertEquals('word here and 45', $this->map->get('foo', array('here', 45)));
	}
	
	/**
	 * This is for backwards compatibility from before we supported sprintf arguments.
	 */
	public function testGetWithoutArugmentsLeavesSprintfPlacholdersAlone() {
		$this->map->addTranslations(array('foo' => 'word %s and %u'));
		
		$this->assertEquals('word %s and %u', $this->map->get('foo'));
	}
	
	public function testAddTranslationsMergesIntoExistingMap() {
		$this->map->addTranslations(array(
			'foo' => 'Foo translation',
			'bar' => 'Bar translation',
		));
		$this->map->addTranslations(array(
			'bar' => 'Bar replacement',
			'baz' => 'Baz replacement',
		));
		
		$this->assertEquals('Foo translation', $this->map->get('foo'));
		$this->assertEquals('Bar replacement', $this->map->get('bar'));
		$this->assertEquals('Baz replacement', $this->map->get('baz'));
	}
}
