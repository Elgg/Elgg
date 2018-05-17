<?php

namespace Elgg\Collections;

use Elgg\UnitTestCase;

/**
 * @group Collections
 */
class CollectionsUnitTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanConstructEmptyCollection() {
		$collection = new Collection();

		$this->assertEmpty($collection->all());
	}

	public function testCanConstructCollectionFromIndexedArray() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $collection->all());

	}

	public function testCanConstructCollectionFromAssocArray() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection(['c' => $a, 'd' => $b]);

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $collection->all());

	}

	public function testCanConstructCollectionFromCollection() {
		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection(['c' => $a, 'd' => $b]);
		$collection = new Collection($collection);

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $collection->all());

	}

	/**
	 * @expectedException \InvalidParameterException
	 */
	public function testCanConstructCollectionWithInvalidItems() {

		_elgg_services()->logger->disable();

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection(['c' => $a, 'd' => $b, null, false, new \stdClass()]);

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $collection->all());

		$errors = _elgg_services()->logger->enable();

		$this->assertCount(3, $errors);
	}

	public function testCanAddItems() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$collection->add(new TestItem('c', 300));

		$collection->merge([new TestItem('d', 300)]);
		$collection->merge(new Collection([new TestItem('e', 400)]));

		$collection[] = new TestItem('f', 500);

		$collection['foo'] = new TestItem('g', 600);

		$collection[] = new TestItem('i', 500);

		$this->assertCount(8, $collection);

	}

	public function testCanRemoveItems() {
		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$this->assertTrue($collection->has('a'));

		$collection->remove('a');

		$this->assertFalse($collection->has('a'));
	}

	public function testCanFillCollection() {
		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);
		$c = new TestItem('c', 300);
		$d = new TestItem('d', 400);

		$collection = new Collection(['c' => $a, 'd' => $b]);

		$collection->fill([$c, $d]);

		$this->assertEquals([
			'c' => $c,
			'd' => $d,
		], $collection->all());
	}

	public function testCanFilterCollectionWithoutCallback() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$filtered = $collection->filter();

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $filtered->all());

		$this->assertNotSame($collection, $filtered);
	}

	public function testCanFilterCollectionWithCallback() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$filtered = $collection->filter(function(TestItem $e) {
			return $e->getPriority() != 200;
		});

		$this->assertEquals([
			'a' => $a,
		], $filtered->all());
	}

	public function testCanSortCollectionWithoutCallback() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$b, $a]);

		$sorted = $collection->sort();

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $sorted->all());

		$this->assertSame($collection, $sorted);
	}

	public function testCanSortCollectionWithCallback() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);
		$c = new TestItem('c', 300);
		$d = new TestItem('d', 400);

		$collection = new Collection([$d, $c, $b, $a]);

		$sorted = $collection->sort(function($a, $b) {
			return strnatcmp($a->getId(), $b->getId());
		});

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
			'c' => $c,
			'd' => $d,
		], $sorted->all());

		$this->assertSame($collection, $sorted);
	}

	public function testCanWalkCollection() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$filtered = $collection->walk(function($e) {
			$e->foo = 'bar';
			return 'whatever';
		});

		$this->assertEquals([
			'a' => $a,
			'b' => $b,
		], $filtered->all());

		$this->assertSame($collection, $filtered);

		$this->assertEquals('bar', $collection->get('a')->foo);
	}

	public function testCanMapCollection() {

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$filtered = $collection->map(function($e) {
			return $e->getPriority();
		});

		$this->assertEquals([
			'a' => 100,
			'b' => 200
		], $filtered);

	}

	public function testCanAccessCollectionAsArray() {
		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$this->assertTrue(isset($collection['a']));
		$this->assertSame($a, $collection['a']);

		$collection['a'] = new TestItem('a', 100);
		$this->assertNotSame($a, $collection['a']);

		unset($collection['a']);
		$this->assertNull($collection->get('a'));
		$this->assertNull($collection['a']);
	}

	public function testCanIterateThroughCollection() {
		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$collection = new Collection([$a, $b]);

		$steps = [
			['a', $a],
			['b', $b],
		];

		$this->assertEquals(2, count($collection));

		$i = 0;
		foreach ($collection as $id => $item) {
			$this->assertEquals($steps[$i], [$id, $item]);
			$i++;
		}

	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConstructorThrowsWithInvalidClass() {
		new Collection([], MyClass::class);
	}

}

class TestItem implements CollectionItemInterface {

	public function __construct($id, $priority) {
		$this->id = $id;
		$this->priority = $priority;
	}

	/**
	 * Get unique item identifier within a collection
	 * @return string|int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get priority (weight) of the item within a collection
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}
}