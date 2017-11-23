<?php

namespace Elgg\Cache;

/**
 * @group UnitTests
 */
class LRUCacheUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testOldestItemsGetDroppedWhenUnused() {
		$pool = new LRUCache(4);

		// foo1 gets dropped
		$pool->set('foo1', 10);
		$pool->set('foo2', 20);
		$pool['foo3'] = 30;
		$pool['foo4'] = 40;
		$pool->set('foo5', 50);

		$this->assertEquals(null, $pool->get('foo1'));
		$this->assertFalse(isset($pool['foo1']));
		$this->assertEquals(20, $pool->get('foo2'));
		$this->assertEquals(30, $pool->get('foo3'));
		$this->assertEquals(40, $pool['foo4']);
		$this->assertEquals(50, $pool['foo5']);
		$this->assertEquals(4, $pool->size());

		// remove item using unset
		unset($pool['foo4']);

		$this->assertFalse(isset($pool['foo4']));
		$this->assertEquals(null, $pool['foo4']);
		$this->assertEquals(3, $pool->size());

		// remove item using method
		$this->assertEquals(20, $pool->remove('foo2'));
		$this->assertEquals(2, $pool->size());
		$this->assertEquals(null, $pool->remove('foo2'));

		// clear the cache
		$pool->clear();
		$this->assertEquals(null, $pool['foo2']);
		$this->assertEquals(0, $pool->size());
	}

	public function testLeastUsedItemGetsDropped() {
		$pool = new LRUCache(2);

		$pool->set('foo1', 10);
		$pool->set('foo2', 25);
		$this->assertEquals(25, $pool->get('foo2'));
		$pool->set('foo2', 20);
		$this->assertEquals(20, $pool->get('foo2'));
		$this->assertEquals(10, $pool->get('foo1'));

		// foo2 was least recently read
		$pool->set('foo3', 30);

		$this->assertEquals(null, $pool->get('foo2'));
		$this->assertFalse(isset($pool['foo2']));
		$this->assertEquals(10, $pool->get('foo1'));
		$this->assertEquals(30, $pool->get('foo3'));
		$this->assertEquals(2, $pool->size());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testThrowExceptionOnNegativeSize() {
		$pool = new LRUCache(-2);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testThrowExceptionOnNonIntSize() {
		$pool = new LRUCache("abc");
	}

}
