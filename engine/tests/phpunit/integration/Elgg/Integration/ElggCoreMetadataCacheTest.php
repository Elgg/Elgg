<?php

namespace Elgg\Integration;

use Elgg\Cache\MetadataCache;
use Elgg\IntegrationTestCase;

/**
 * Elgg Test metadata cache
 *
 * @group IntegrationTests
 * @group Metadata
 * @group MetadataCache
 * @group Cache
 */
class ElggCoreMetadataCacheTest extends IntegrationTestCase {

	/**
	 * @var MetadataCache
	 */
	protected $cache;

	protected $ignoreAccess;

	public function up() {
		$this->ignoreAccess = elgg_set_ignore_access(false);

		$this->cache = _elgg_services()->metadataCache;
	}

	public function down() {

		elgg_set_ignore_access($this->ignoreAccess);

		$this->cache->clearAll();
	}

	public function testHas() {

		$this->cache->inject(1, ['foo1' => 'bar']);
		$this->cache->inject(2, []);

		$this->assertTrue($this->cache->isLoaded(1));
		$this->assertTrue($this->cache->isLoaded(2));
	}

	public function testLoad() {

		$this->cache->inject(1, ['foo1' => 'bar']);
		$this->cache->inject(2, []);

		$this->assertEquals($this->cache->getSingle(1, 'foo1'), 'bar');
		$this->assertNull($this->cache->getSingle(1, 'foo2'));
		$this->assertNull($this->cache->getSingle(2, 'foo1'));
	}

	public function testLoadAll() {

		$this->cache->inject(1, ['foo1' => 'bar', 'foo2' => ['bar1', 'bar2']]);
		$this->cache->inject(2, []);

		$this->assertEquals($this->cache->getAll(1), [
			'foo1' => 'bar',
			'foo2' => ['bar1', 'bar2'],
		]);

		$this->assertEmpty($this->cache->getAll(2));
	}

	public function testDirectInvalidation() {

		$this->cache->inject(1, ['foo1' => 'bar']);
		$this->cache->inject(2, ['foo2' => 'bar']);

		$this->cache->invalidateByOptions(['guid' => 1]);
		$this->assertFalse($this->cache->isLoaded(1));
		$this->assertTrue($this->cache->isLoaded(2));

		$this->cache->invalidateByOptions([]);
		$this->assertFalse($this->cache->isLoaded(1));
		$this->assertFalse($this->cache->isLoaded(2));
	}

	public function testMetadataReadsFillsCache() {
		$object = $this->createObject();

		// test that reads fill cache
		$object->setMetadata('test_metadata', [1, 2]);
		$this->cache->clearAll();

		$object->getMetadata('test_metadata');
		$this->assertEquals([1, 2], $this->cache->getSingle($object->guid, 'test_metadata'));

		$object->delete();
	}

	public function testDeleteInvalidatesCache() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		$guid1 = $object1->guid;
		$guid2 = $object2->guid;

		// Deleting by entity guid should invalidate cache for that entity
		$this->cache->inject($guid1, ['foo' => 'bar']);
		$this->cache->inject($guid2, ['bing' => 'bar']);
		elgg_delete_metadata(array(
			'guid' => $guid1,
		));
		$this->assertFalse($this->cache->isLoaded($guid1));
		$this->assertTrue($this->cache->isLoaded($guid2));

		// Deleting by multiple guids should invalidate cache for those entities
		$this->cache->inject($guid1, ['foo' => 'bar']);
		$this->cache->inject($guid2, ['bing' => 'bar']);
		elgg_delete_metadata([
			'guids' => [$guid1, $guid2],
		]);
		$this->assertFalse($this->cache->isLoaded($guid1));
		$this->assertFalse($this->cache->isLoaded($guid2));

		// Deleting with specific metadata names should only invalidate cache for those metadata names
		$this->cache->inject($guid1, [
			'foo' => 'bar',
			'unfoo' => 'unbar',
		]);

		$this->cache->inject($guid2, [
			'bing' => 'bar',
			'unbing' => 'unbar',
		]);

		elgg_delete_metadata([
			'guids' => [$guid1, $guid2],
			'metadata_names' => ['foo', 'bing'],
		]);

		$this->assertTrue($this->cache->isLoaded($guid1));
		$this->assertTrue($this->cache->isLoaded($guid2));

		$index = function(array $values) {
			$return = [];
			foreach ($values as $value) {
				$name = $value->name;
				$return[$name] = $value->value;
			}

			return $return;
		};

		$cache1 = $index($this->cache->getCache()->load($guid1));
		$cache2 = $index($this->cache->getCache()->load($guid2));

		$this->assertNull(elgg_extract('foo', $cache1));
		$this->assertEquals('unbar', elgg_extract('unfoo', $cache1));

		$this->assertNull(elgg_extract('bing', $cache2));
		$this->assertEquals('unbar', elgg_extract('unbing', $cache2));

		$object1->delete();
		$object2->delete();
	}

	public function testPopulateFromEntities() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		$guid1 = $object1->guid;
		$guid2 = $object2->guid;

		// test populating cache from set of entities
		$object1->setMetadata('test_metadata', 'test_metadata');
		$object1->setMetadata('test_metadata', 4, 'integer', true);
		$object1->setMetadata("test_metadata-2", "test_metadata-2");
		$object2->setMetadata('test_metadata', 'test_metadata');

		$this->cache->clearAll();
		$this->cache->populateFromEntities(array($object1->guid, $guid2));

		$this->assertEquals([
			'test_metadata',
			4,
		], $this->cache->getSingle($guid1, 'test_metadata'));
		$this->assertEquals("test_metadata-2", $this->cache->getSingle($guid1, "test_metadata-2"));
		$this->assertEquals('test_metadata', $this->cache->getSingle($guid2, 'test_metadata'));

		$object1->delete();
		$object2->delete();
	}

	public function testFilterHeavyEntities() {
		$object1 = $this->createObject();
		$object2 = $this->createObject();

		$guid1 = $object1->guid;
		$guid2 = $object2->guid;

		$big_str = str_repeat('-', 5000);
		$object2->setMetadata('test_metadata', array($big_str, $big_str));

		$guids = array($guid1, $guid2);
		$expected = array($guid1);
		$actual = $this->cache->filterMetadataHeavyEntities($guids, 6000);
		$this->assertEquals($expected, $actual);

		$object1->delete();
		$object2->delete();
	}

	public function testMetadataCanBeLoadedWithCacheDisabled() {
		$object1 = $this->createObject();
		$object1->foo = 'bar';

		_elgg_disable_caches();

		$object = get_entity($object1->guid);
		$this->assertInstanceOf(\ElggObject::class, $object);
		$this->assertEquals($object->guid, $object1->guid);

		$this->assertEquals('bar', $object->foo);

		_elgg_enable_caches();

		$object = get_entity($object1->guid);
		$this->assertInstanceOf(\ElggObject::class, $object);
		$this->assertEquals($object->guid, $object1->guid);

		$this->assertEquals('bar', $object->foo);
	}
}
