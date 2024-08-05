<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

class ElggCoreMetadataCacheTest extends IntegrationTestCase {

	public function testPopulateFromEntities() {
		$cache = _elgg_services()->metadataCache;
		$object1 = $this->createObject();
		$object2 = $this->createObject();

		// test populating cache from set of entities
		$object1->setMetadata('test_metadata', 'test_metadata');
		$object1->setMetadata('test_metadata', 4, 'integer', true);
		$object1->setMetadata('test_metadata-2', 'test_metadata-2');
		$object2->setMetadata('test_metadata', 'test_metadata');
		
		_elgg_services()->metadataCache->clear();
		$this->assertNull($cache->load($object1->guid));
		$this->assertNull($cache->load($object2->guid));
		
		_elgg_services()->metadataCache->populateFromEntities([$object1->guid, $object2->guid]);
		
		$this->assertIsArray($cache->load($object1->guid));
		$this->assertIsArray($cache->load($object2->guid));
		
		// testing object1
		$metadata = $cache->load($object1->guid);
		
		$test_value1 = null;
		$test_value2 = null;
		foreach ($metadata as $md) {
			if ($md->name === 'test_metadata') {
				$test_value1 = $md->value;
			}
			
			if ($md->name === 'test_metadata-2') {
				$test_value2 = $md->value;
			}
		}
		
		$this->assertEquals(4, $test_value1);
		$this->assertEquals('test_metadata-2', $test_value2);
		
		// testing object2
		$metadata = $cache->load($object2->guid);
		
		$test_value1 = null;
		foreach ($metadata as $md) {
			if ($md->name === 'test_metadata') {
				$test_value1 = $md->value;
			}
		}
		
		$this->assertEquals('test_metadata', $test_value1);
	}
}
