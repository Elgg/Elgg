<?php

namespace Elgg\Structs\Collection;

/**
 * @group UnitTests
 */
class InMemoryUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCountIsAccurate() {
		$zeroItems = InMemory::fromArray([]);
		$this->assertEquals(0, count($zeroItems));

		$oneItem = InMemory::fromArray(['one']);
		$this->assertEquals(1, count($oneItem));

		$twoItems = InMemory::fromArray(['one', 'two']);
		$this->assertEquals(2, count($twoItems));
	}

	public function testContainsDoesNotImplicitlyCastSimilarValues() {
		$collection = InMemory::fromArray(['1', false]);

		$this->assertTrue($collection->contains('1'));
		$this->assertTrue($collection->contains(false));

		$this->assertFalse($collection->contains(1));
		$this->assertFalse($collection->contains(0));
		$this->assertFalse($collection->contains(''));
	}

	public function testIsTraversable() {
		$collection = InMemory::fromArray(['one', 'two', 'three']);

		$items = array();
		foreach ($collection as $item) {
			$items[] = $item;
		}

		$this->assertEquals(array('one', 'two', 'three'), $items);
	}

	public function testIsFilterable() {
		$collection = InMemory::fromArray([0, 1, 2, 3, 4]);

		$filtered = $collection->filter(function($number) {
			return $number > 2;
		});

		$this->assertFalse($filtered->contains(0));
		$this->assertFalse($filtered->contains(1));
		$this->assertFalse($filtered->contains(2));
		$this->assertTrue($filtered->contains(3));
		$this->assertTrue($filtered->contains(4));
		$this->assertEquals(2, count($filtered));
		$this->assertNotSame($filtered, $collection);
	}

	public function testIsMappable() {
		$collection = InMemory::fromArray([0, 1, 2, 3, 4]);

		$mapped = $collection->map(function($number) {
			return $number * 2;
		});

		$this->assertTrue($mapped->contains(0));
		$this->assertTrue($mapped->contains(2));
		$this->assertTrue($mapped->contains(4));
		$this->assertTrue($mapped->contains(6));
		$this->assertTrue($mapped->contains(8));
		$this->assertEquals(5, count($mapped));
		$this->assertNotSame($mapped, $collection);
	}

}
