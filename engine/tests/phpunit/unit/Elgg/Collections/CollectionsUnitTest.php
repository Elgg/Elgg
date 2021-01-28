<?php

namespace Elgg\Collections;

use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;
use Elgg\Helpers\Collections\TestItem;

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

	public function testCanConstructCollectionWithInvalidItems() {

		_elgg_services()->logger->disable();

		$a = new TestItem('a', 100);
		$b = new TestItem('b', 200);

		$this->expectException(InvalidParameterException::class);
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
			return strnatcmp($a->getID(), $b->getID());
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

	public function testConstructorThrowsWithInvalidClass() {
		$this->expectException(InvalidArgumentException::class);
		new Collection([], MyClass::class);
	}
	
	public function testSeekeableIteratorInterface() {
		$items = [
			new TestItem('a', 10),
			new TestItem('b', 20),
			new TestItem('c', 30),
			new TestItem('d', 40),
			new TestItem('e', 50),
			new TestItem('f', 60),
			new TestItem('g', 70),
			new TestItem('h', 80),
			new TestItem('i', 90),
			new TestItem('j', 100),
		];
		$collection = new Collection($items);
		
		$this->assertCount(10, $collection);
		
		$collection->seek(3);
		$this->assertEquals('d', $collection->key());
		$this->assertEquals($items[3], $collection->current());
		
		$collection->next();
		$this->assertEquals('e', $collection->key());
		$this->assertEquals($items[4], $collection->current());
		
		$collection->rewind();
		$this->assertEquals('a', $collection->key());
		$this->assertEquals($items[0], $collection->current());
		
		$collection->seek(9); // move to last item
		$this->assertTrue($collection->valid());
		$collection->next(); // move out of the available items
		$this->assertFalse($collection->valid());
		
		$this->expectException(\OutOfBoundsException::class);
		$collection->seek(10);
	}
}
