<?php

/**
 * @group UnitTests
 */
class ElggPriorityListUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testAdd() {
		$pl = new \ElggPriorityList();
		$elements = array(
			'Test value',
			'Test value 2',
			'Test value 3'
		);

		shuffle($elements);

		foreach ($elements as $element) {
			$this->assertTrue($pl->add($element) !== false);
		}

		$test_elements = $pl->getElements();

		$this->assertTrue(is_array($test_elements));

		foreach ($test_elements as $i => $element) {
			// should be in the array
			$this->assertTrue(in_array($element, $elements));

			// should be the only element, so priority 0
			$this->assertEquals($i, array_search($element, $elements));
		}
	}

	public function testAddWithPriority() {
		$pl = new \ElggPriorityList();

		$elements = array(
			10 => 'Test Element 10',
			5 => 'Test Element 5',
			0 => 'Test Element 0',
			100 => 'Test Element 100',
			-1 => 'Test Element -1',
			-5 => 'Test Element -5',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		$test_elements = $pl->getElements();

		// should be sorted by priority
		$elements_sorted = array(
			-5 => 'Test Element -5',
			-1 => 'Test Element -1',
			0 => 'Test Element 0',
			5 => 'Test Element 5',
			10 => 'Test Element 10',
			100 => 'Test Element 100',
		);

		$this->assertSame($elements_sorted, $test_elements);

		foreach ($test_elements as $priority => $element) {
			$this->assertSame($elements[$priority], $element);
		}
	}

	public function testGetNextPriority() {
		$pl = new \ElggPriorityList();

		$elements = array(
			2 => 'Test Element',
			0 => 'Test Element 2',
			-2 => 'Test Element 3',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		// we're not specifying a priority so it should be the next consecutive to 0.
		$this->assertEquals(1, $pl->getNextPriority());

		// add another one at priority 1
		$pl->add('Test Element 1');

		// next consecutive to 0 is now 3.
		$this->assertEquals(3, $pl->getNextPriority());
	}

	public function testRemove() {
		$pl = new \ElggPriorityList();

		$elements = array();
		for ($i = 0; $i < 3; $i++) {
			$element = new \stdClass();
			$element->name = "Test Element $i";
			$element->someAttribute = rand(0, 9999);
			$elements[] = $element;
			$pl->add($element);
		}

		$pl->remove($elements[1]);

		$test_elements = $pl->getElements();

		// make sure it's gone.
		$this->assertEquals(2, count($test_elements));
		$this->assertSame($elements[0], $test_elements[0]);
		$this->assertSame($elements[2], $test_elements[2]);
	}

	public function testMove() {
		$pl = new \ElggPriorityList();

		$elements = array(
			-5 => 'Test Element -5',
			0 => 'Test Element 0',
			5 => 'Test Element 5',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		$this->assertEquals($pl->move($elements[-5], 10), 10);

		// check it's at the new place
		$this->assertSame($elements[-5], $pl->getElement(10));

		// check it's not at the old
		$this->assertFalse($pl->getElement(-5));
	}

	public function testConstructor() {
		$elements = array(
			10 => 'Test Element 10',
			5 => 'Test Element 5',
			0 => 'Test Element 0',
			100 => 'Test Element 100',
			-1 => 'Test Element -1',
			-5 => 'Test Element -5'
		);

		$pl = new \ElggPriorityList($elements);
		$test_elements = $pl->getElements();

		$elements_sorted = array(
			-5 => 'Test Element -5',
			-1 => 'Test Element -1',
			0 => 'Test Element 0',
			5 => 'Test Element 5',
			10 => 'Test Element 10',
			100 => 'Test Element 100',
		);

		$this->assertSame($elements_sorted, $test_elements);
	}

	public function testGetPriority() {
		$pl = new \ElggPriorityList();

		$elements = array(
			'Test element 0',
			'Test element 1',
			'Test element 2',
		);

		foreach ($elements as $element) {
			$pl->add($element);
		}

		$this->assertSame(0, $pl->getPriority($elements[0]));
		$this->assertSame(1, $pl->getPriority($elements[1]));
		$this->assertSame(2, $pl->getPriority($elements[2]));
	}

	public function testGetElement() {
		$pl = new \ElggPriorityList();
		$priorities = array();

		$elements = array(
			'Test element 0',
			'Test element 1',
			'Test element 2',
		);

		foreach ($elements as $element) {
			$priorities[] = $pl->add($element);
		}

		$this->assertSame($elements[0], $pl->getElement($priorities[0]));
		$this->assertSame($elements[1], $pl->getElement($priorities[1]));
		$this->assertSame($elements[2], $pl->getElement($priorities[2]));
	}

	public function testPriorityCollision() {
		$pl = new \ElggPriorityList();

		$elements = array(
			5 => 'Test element 5',
			6 => 'Test element 6',
			0 => 'Test element 0',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		// add at a colliding priority
		$pl->add('Colliding element', 5);

		// should float to the top closest to 5, so 7
		$this->assertEquals(7, $pl->getPriority('Colliding element'));
	}

	public function testIterator() {
		$elements = array(
			-5 => 'Test element -5',
			0 => 'Test element 0',
			5 => 'Test element 5',
		);

		$pl = new \ElggPriorityList($elements);

		foreach ($pl as $priority => $element) {
			$this->assertSame($elements[$priority], $element);
		}
	}

	public function testCountable() {
		$pl = new \ElggPriorityList();

		$this->assertEquals(0, count($pl));

		$pl->add('Test element 0');
		$this->assertEquals(1, count($pl));

		$pl->add('Test element 1');
		$this->assertEquals(2, count($pl));

		$pl->add('Test element 2');
		$this->assertEquals(3, count($pl));
	}

	public function testUserSort() {
		$elements = array(
			'A',
			'B',
			'C',
			'D',
			'E',
		);

		$elements_sorted_string = $elements;

		shuffle($elements);
		$pl = new \ElggPriorityList($elements);

		// will sort by priority
		$test_elements = $pl->getElements();
		$this->assertSame($elements, $test_elements);

		function test_sort($elements) {
			sort($elements, SORT_LOCALE_STRING);
			return $elements;
		}

		// force a new sort using our function
		$pl->sort('test_sort');
		$test_elements = $pl->getElements();

		$this->assertSame($elements_sorted_string, $test_elements);
	}

}
