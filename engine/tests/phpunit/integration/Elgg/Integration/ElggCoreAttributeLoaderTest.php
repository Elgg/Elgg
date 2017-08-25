<?php

namespace Elgg\Integration;

use Elgg\Config;
use Elgg\LegacyIntegrationTestCase;

/**
 * Test attribute loader for entities
 *
 * @group IntegrationTests
 * @group AttributeLoader
 */
class ElggCoreAttributeLoaderTest extends LegacyIntegrationTestCase {

	/**
	 * @var array
	 */
	private $guids;

	public function up() {

	}

	public function down() {

	}

	public function entityTypeProvider() {
		$provides = [];

		foreach (Config::getEntityTypes() as $type) {
			$provides[] = [$type];
		}

		return $provides;
	}

	/**
	 * Checks if additional select columns are readable as volatile data
	 *
	 * https://github.com/Elgg/Elgg/issues/5543
	 *
	 * @dataProvider entityTypeProvider
	 */
	public function testSqlAdditionalSelectsAsVolatileData($type) {

		$this->createOne($type);

		$entities = elgg_get_entities([
			'guids' => $this->guids,
			'type' => $type,
			'selects' => ['42 as added_col2'],
			'limit' => 1,
		]);

		$this->assertFalse(empty($entities));

		if ($entities) {
			$entity = array_shift($entities);
			$this->assertTrue($entity instanceof \ElggEntity);
			$this->assertEqual($entity->added_col2, null, "Additional select columns are leaking to attributes for " . get_class($entity));
			$this->assertEqual($entity->getVolatileData('select:added_col2'), 42);
		}

	}

	/**
	 * Checks if additional select columns are readable as volatile data even if we hit the cache while fetching entity.
	 *
	 * https://github.com/Elgg/Elgg/issues/5544
	 *
	 * @dataProvider entityTypeProvider
	 */
	public function testSqlAdditionalSelectsAsVolatileDataWithCache($type) {

		$this->createOne($type);
		
		$entities = elgg_get_entities([
			'guids' => $this->guids,
			'type' => $type,
			'selects' => ['42 as added_col3'],
			'limit' => 1,
		]);

		$this->assertFalse(empty($entities));

		if ($entities) {
			$entity = array_shift($entities);
			$this->assertTrue($entity instanceof \ElggEntity);
			$this->assertEqual($entity->added_col3, null, "Additional select columns are leaking to attributes for " . get_class($entity));
			$this->assertEqual($entity->getVolatileData('select:added_col3'), 42);

			// make sure we have cached the entity
			$this->assertNotEqual(false, elgg_get_session()->entityCache->get($entity->guid));
		}

		// run these again but with different value to make sure cache does not interfere
		$entities = elgg_get_entities([
			'guids' => $this->guids,
			'type' => $type,
			'selects' => ['64 as added_col3'],
			'limit' => 1,
		]);

		$this->assertFalse(empty($entities));

		if ($entities) {
			$entity = array_shift($entities);
			$this->assertTrue($entity instanceof \ElggEntity);
			$this->assertEqual($entity->added_col3, null, "Additional select columns are leaking to attributes for " . get_class($entity));
			$this->assertEqual($entity->getVolatileData('select:added_col3'), 64, "Failed to overwrite volatile data in cached entity");
		}
	}

}
