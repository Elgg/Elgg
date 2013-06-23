<?php

/**
 * Test attribute loader for entities
 */
class ElggCoreAttributeLoaderTest extends ElggCoreUnitTest {

	/**
	 * Checks if additional select columns are readable as volatile data
	 *
	 * https://github.com/Elgg/Elgg/issues/5543
	 */
	public function testSqlAdditionalSelectsAsVolatileData() {

		// remove ignore access as it disables entity cache
		$access = elgg_set_ignore_access(false);

		// may not have groups in DB but guaranteed to have user, object, site
		$group = new ElggGroup();
		$group->name = 'test_group';
		$group->access_id = ACCESS_PUBLIC;
		$this->assertTrue($group->save() !== false);

		foreach (array('site', 'user', 'group', 'object') as $type) {
			$entities = elgg_get_entities(array(
				'type' => $type,
				'selects' => array('42 as added_col2'),
				'limit' => 1,
			));
			$entity = array_shift($entities);
			$this->assertTrue($entity instanceof ElggEntity);
			$this->assertEqual($entity->added_col2, null, "Additional select columns are leaking to attributes for " . get_class($entity));
			$this->assertEqual($entity->getVolatileData('select:added_col2'), 42);
		}

		elgg_set_ignore_access($access);

		$group->delete();
	}

	/**
	 * Checks if additional select columns are readable as volatile data even if we hit the cache while fetching entity.
	 *
	 * https://github.com/Elgg/Elgg/issues/5544
	 */
	public function testSqlAdditionalSelectsAsVolatileDataWithCache() {

		// remove ignore access as it disables entity cache
		$access = elgg_set_ignore_access(false);

		// may not have groups in DB - let's create one
		$group = new ElggGroup();
		$group->name = 'test_group';
		$group->access_id = ACCESS_PUBLIC;
		$this->assertTrue($group->save() !== false);

		foreach (array('site', 'user', 'group', 'object') as $type) {
			$entities = elgg_get_entities(array(
				'type' => $type,
				'selects' => array('42 as added_col3'),
				'limit' => 1,
			));
			$entity = array_shift($entities);
			$this->assertTrue($entity instanceof ElggEntity);
			$this->assertEqual($entity->added_col3, null, "Additional select columns are leaking to attributes for " . get_class($entity));
			$this->assertEqual($entity->getVolatileData('select:added_col3'), 42);

			// make sure we have cached the entity
			$this->assertNotEqual(false, _elgg_retrieve_cached_entity($entity->guid));
		}

		// run these again but with different value to make sure cache does not interfere
		foreach (array('site', 'user', 'group', 'object') as $type) {
			$entities = elgg_get_entities(array(
				'type' => $type,
				'selects' => array('64 as added_col3'),
				'limit' => 1,
			));
			$entity = array_shift($entities);
			$this->assertTrue($entity instanceof ElggEntity);
			$this->assertEqual($entity->added_col3, null, "Additional select columns are leaking to attributes for " . get_class($entity));
			$this->assertEqual($entity->getVolatileData('select:added_col3'), 64, "Failed to overwrite volatile data in cached entity");
		}

		elgg_set_ignore_access($access);

		$group->delete();
	}

}
