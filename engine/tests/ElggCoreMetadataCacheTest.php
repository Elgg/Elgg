<?php

use Elgg\Cache\MetadataCache;

/**
 * Elgg Test metadata cache
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreMetadataCacheTest extends \ElggCoreUnitTest {

	/**
	 * @var MetadataCache
	 */
	protected $cache;

	/**
	 * @var \ElggObject
	 */
	protected $obj1;

	/**
	 * @var int
	 */
	protected $guid1;

	/**
	 * @var \ElggObject
	 */
	protected $obj2;

	/**
	 * @var int
	 */
	protected $guid2;

	protected $name = 'test';
	protected $value = 'test';
	protected $ignoreAccess;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->ignoreAccess = elgg_set_ignore_access(false);

		$this->cache = _elgg_services()->metadataCache;

		$this->obj1 = new \ElggObject();
		$this->obj1->save();
		$this->guid1 = $this->obj1->guid;

		$this->obj2 = new \ElggObject();
		$this->obj2->save();
		$this->guid2 = $this->obj2->guid;
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->obj1->delete();
		$this->obj2->delete();

		elgg_set_ignore_access($this->ignoreAccess);
	}

	public function testHas() {
		$cache = new MetadataCache(ElggSession::getMock());

		$cache->inject(1, ['foo1' => 'bar']);
		$cache->inject(2, []);
		$this->assertTrue($cache->isLoaded(1));
		$this->assertTrue($cache->isLoaded(2));
		$this->assertFalse($cache->isLoaded(3));
	}

	public function testLoad() {
		$cache = new MetadataCache(ElggSession::getMock());

		$cache->inject(1, ['foo1' => 'bar']);
		$cache->inject(2, []);

		$this->assertIdentical($cache->getSingle(1, 'foo1'), 'bar');
		$this->assertNull($cache->getSingle(1, 'foo2'));
		$this->assertNull($cache->getSingle(2, 'foo1'));
	}

	public function testDirectInvalidation() {
		$cache = new MetadataCache(ElggSession::getMock());

		$cache->inject(1, ['foo1' => 'bar']);
		$cache->inject(2, []);

		$cache->invalidateByOptions(['guid' => 1]);
		$this->assertFalse($cache->isLoaded(1));
		$this->assertTrue($cache->isLoaded(2));

		$cache->invalidateByOptions([]);
		$this->assertFalse($cache->isLoaded(1));
		$this->assertFalse($cache->isLoaded(2));
	}

	public function testCacheIsSegregatedByAccessState() {
		$session = ElggSession::getMock();
		$cache = new MetadataCache($session);
		$cache->inject(1, ['foo' => 'bar']);

		$session->setIgnoreAccess();
		$this->assertFalse($cache->isLoaded(1));

		$session->setIgnoreAccess(false);
		$this->assertTrue($cache->isLoaded(1));

		$user = elgg_get_entities(['type' => 'user', 'limit' => 1]);
		$user = $user[0];
		$cache->inject(1, ['foo' => 'bar']);

		$session->setLoggedInUser($user);
		$this->assertFalse($cache->isLoaded(1));
	}

	public function testClearActsOnAllAccessStates() {
		$session = ElggSession::getMock();
		$cache = new MetadataCache($session);

		$session->setIgnoreAccess(false);
		$cache->inject(1, ['foo' => 'bar']);

		$session->setIgnoreAccess(true);
		$cache->clear(1);
		$session->setIgnoreAccess(false);
		$this->assertFalse($cache->isLoaded(1));

		$session->setIgnoreAccess(true);
		$cache->inject(1, ['foo' => 'bar']);

		$session->setIgnoreAccess(false);
		$cache->clear(1);
		$session->setIgnoreAccess(true);
		$this->assertFalse($cache->isLoaded(1));
	}

	public function testMetadataReadsFillsCache() {
		// test that reads fill cache
		$this->obj1->setMetadata($this->name, [1, 2]);
		$this->cache->clearAll();

		$this->obj1->getMetadata($this->name);
		$this->assertIdentical($this->cache->getSingle($this->guid1, $this->name), [1, 2]);
	}

	public function testWritesInvalidate() {
		// elgg_delete_metadata
		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		$this->cache->inject($this->guid2, ['bing' => 'bar']);
		elgg_delete_metadata(array(
			'guid' => $this->guid1,
		));
		$this->assertFalse($this->cache->isLoaded($this->guid1));
		$this->assertTrue($this->cache->isLoaded($this->guid2));

		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		$this->cache->inject($this->guid2, ['bing' => 'bar']);
		elgg_delete_metadata(['guids' => [$this->guid1, $this->guid2]]);
		$this->assertFalse($this->cache->isLoaded($this->guid1));
		$this->assertFalse($this->cache->isLoaded($this->guid2));

		// setMetadata
		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		$this->obj1->setMetadata($this->name, $this->value);
		$this->assertFalse($this->cache->isLoaded($this->obj1));

		// deleteMetadata
		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		$this->obj1->deleteMetadata($this->name);
		$this->assertFalse($this->cache->isLoaded($this->guid1));

		// create_metadata
		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		create_metadata($this->guid1, 'foo', 'bar', 'text');
		$this->assertFalse($this->cache->isLoaded($this->guid1));

		// disableMetadata
		$this->obj1->setMetadata($this->name, $this->value);
		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		$this->obj1->disableMetadata($this->name);
		$this->assertFalse($this->cache->isLoaded($this->guid1));

		// enableMetadata
		$this->cache->inject($this->guid1, ['foo' => 'bar']);
		$this->obj1->enableMetadata($this->name);
		$this->assertFalse($this->cache->isLoaded($this->guid1));
	}

	public function testPopulateFromEntities() {
		// test populating cache from set of entities
		$this->obj1->setMetadata($this->name, $this->value);
		$this->obj1->setMetadata($this->name, 4, 'integer', true);
		$this->obj1->setMetadata("{$this->name}-2", "{$this->value}-2");
		$this->obj2->setMetadata($this->name, $this->value);

		$this->cache->clearAll();
		$this->cache->populateFromEntities(array($this->guid1, $this->guid2));

		$this->assertIdentical($this->cache->getSingle($this->guid1, $this->name), [
			$this->value,
			4,
		]);
		$this->assertIdentical($this->cache->getSingle($this->guid1, "{$this->name}-2"), "{$this->value}-2");
		$this->assertIdentical($this->cache->getSingle($this->guid2, $this->name), $this->value);
	}

	public function testFilterHeavyEntities() {
		$big_str = str_repeat('-', 5000);
		$this->obj2->setMetadata($this->name, array($big_str, $big_str));

		$guids = array($this->guid1, $this->guid2);
		$expected = array($this->guid1);
		$actual = $this->cache->filterMetadataHeavyEntities($guids, 6000);
		$this->assertIdentical($actual, $expected);
	}
}
