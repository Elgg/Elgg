<?php
namespace Elgg\Structs;

use PHPUnit_Framework_TestCase as TestCase;

class ArrayCollectionTest extends TestCase {
	public function testCountIsAccurate() {
		$zeroItems = new ArrayCollection();
		$this->assertEquals(0, count($zeroItems));
		
		$oneItem = new ArrayCollection(array('one'));
		$this->assertEquals(1, count($oneItem));
		
		$twoItems = new ArrayCollection(array('one', 'two'));
		$this->assertEquals(2, count($twoItems));
	}
	
	public function testContainsDoesNotImplicitlyCastSimilarValues() {
		$collection = new ArrayCollection(array('1', false));
		
		$this->assertTrue($collection->contains('1'));
		$this->assertTrue($collection->contains(false));

		$this->assertFalse($collection->contains(1));
		$this->assertFalse($collection->contains(0));
		$this->assertFalse($collection->contains(''));
	}
	
	public function testIsTraversable() {
		$collection = new ArrayCollection(array('one', 'two', 'three'));
		
		$items = array();
		foreach ($collection as $item) {
			$items[] = $item;
		}
		
		$this->assertEquals(array('one', 'two', 'three'), $items);
	}
	
	public function testIsFilterable() {
		$collection = new ArrayCollection(array(0, 1, 2, 3, 4));
		
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
		$collection = new ArrayCollection(array(0, 1, 2, 3, 4));
		
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
