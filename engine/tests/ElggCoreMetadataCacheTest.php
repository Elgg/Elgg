<?php
/**
 * Elgg Test metadata cache
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreMetadataCacheTest extends ElggCoreUnitTest {

	/**
	 * @var ElggVolatileMetadataCache
	 */
	protected $cache;

	/**
	 * @var ElggObject
	 */
	protected $obj1;

	/**
	 * @var int
	 */
	protected $guid1;

	/**
	 * @var ElggObject
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

		$this->cache = _elgg_get_metadata_cache();

		$this->obj1 = new ElggObject();
		$this->obj1->save();
		$this->guid1 = $this->obj1->guid;

		$this->obj2 = new ElggObject();
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

	public function testBasicApi() {
		// test de-coupled instance
		$cache = new ElggVolatileMetadataCache();
		$cache->setIgnoreAccess(false);
		$guid = 1;

		$this->assertFalse($cache->isKnown($guid, $this->name));

		$cache->markEmpty($guid, $this->name);
		$this->assertTrue($cache->isKnown($guid, $this->name));
		$this->assertNull($cache->load($guid, $this->name));

		$cache->markUnknown($guid, $this->name);
		$this->assertFalse($cache->isKnown($guid, $this->name));

		$cache->save($guid, $this->name, $this->value);
		$this->assertIdentical($cache->load($guid, $this->name), $this->value);

		$cache->save($guid, $this->name, 1, true);
		$this->assertIdentical($cache->load($guid, $this->name), array($this->value, 1));

		$cache->clear($guid);
		$this->assertFalse($cache->isKnown($guid, $this->name));
	}

	public function testReadsAreCached() {
		// test that reads fill cache
		$this->obj1->setMetadata($this->name, $this->value);
		$this->cache->flush();

		$this->obj1->getMetadata($this->name);
		$this->assertIdentical($this->cache->load($this->guid1, $this->name), $this->value);
	}

	public function testWritesAreCached() {
		// delete should mark cache as known to be empty
		$this->obj1->deleteMetadata($this->name);
		$this->assertTrue($this->cache->isKnown($this->guid1, $this->name));
		$this->assertNull($this->cache->load($this->guid1, $this->name));

		// without name, delete should invalidate the entire entity
		$this->cache->save($this->guid1, $this->name, $this->value);
		elgg_delete_metadata(array(
			'guid' => $this->guid1,
		));
		$this->assertFalse($this->cache->isKnown($this->guid1, $this->name));

		// test set
		$this->obj1->setMetadata($this->name, $this->value);
		$this->assertIdentical($this->cache->load($this->guid1, $this->name), $this->value);

		// test set multiple
		$this->obj1->setMetadata($this->name, 1, 'integer', true);
		$this->assertIdentical($this->cache->load($this->guid1, $this->name), array($this->value, 1));

		// writes when access is ignore should invalidate
		$tmp_ignore = elgg_set_ignore_access(true);
		$this->obj1->setMetadata($this->name, $this->value);
		$this->assertFalse($this->cache->isKnown($this->guid1, $this->name));
		elgg_set_ignore_access($tmp_ignore);
	}

	public function testDisableAndEnable() {
		// both should mark cache unknown
		$this->obj1->setMetadata($this->name, $this->value);
		$this->obj1->disableMetadata($this->name);
		$this->assertFalse($this->cache->isKnown($this->guid1, $this->name));

		$this->cache->save($this->guid1, $this->name, $this->value);
		$this->obj1->enableMetadata($this->name);
		$this->assertFalse($this->cache->isKnown($this->guid1, $this->name));
	}

	public function testPopulateFromEntities() {
		// test populating cache from set of entities
		$this->obj1->setMetadata($this->name, $this->value);
		$this->obj1->setMetadata($this->name, 4, 'integer', true);
		$this->obj1->setMetadata("{$this->name}-2", "{$this->value}-2");
		$this->obj2->setMetadata($this->name, $this->value);

		$this->cache->flush();
		$this->cache->populateFromEntities(array($this->guid1, $this->guid2));

		$expected = array();
		$expected[$this->name][] = $this->value;
		$expected[$this->name][] = 4;
		$expected["{$this->name}-2"] = "{$this->value}-2";
		$this->assertIdentical($this->cache->loadAll($this->guid1), $expected);

		$expected = array();
		$expected[$this->name] = $this->value;
		$this->assertIdentical($this->cache->loadAll($this->guid2), $expected);
	}

	public function testFilterHeavyEntities() {
		$big_str = str_repeat('-', 5000);
		$this->obj2->setMetadata($this->name, array($big_str, $big_str));

		$guids = array($this->guid1, $this->guid2);
		$expected = array($this->guid1);
		$actual = $this->cache->filterMetadataHeavyEntities($guids, 6000);
		$this->assertIdentical($actual, $expected);
	}

	public function testCreateMetadataInvalidates() {
		$this->obj1->foo = 1;
		create_metadata($this->guid1, 'foo', 2, '', elgg_get_logged_in_user_guid(), ACCESS_FRIENDS);

		$this->assertEqual($this->obj1->foo, 2);
	}
}
