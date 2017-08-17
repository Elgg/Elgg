<?php

namespace Elgg\Database;

/**
 * @group UnitTests
 */
class RelationshipsUnitTest extends \Elgg\UnitTestCase {

	private $guids;

	public function up() {
		$this->markTestSkipped("Skipped test as Elgg can not yet run PHP Unit tests that interact with the database");

		$this->guids = [];

		$obj1 = new \ElggObject();
		$obj1->save();

		$this->guids[] = $obj1->guid;

		$obj2 = new \ElggObject();
		$obj2->save();

		$this->guids[] = $obj2->guid;
	}

	public function down() {
		foreach ($this->guids as $guid) {
			$e = get_entity($guid);
			$e->delete();
		}
	}

	/**
	 * Check that you can get entities by filtering them on relationship time created lower
	 */
	public function testGetEntitiesFromRelationshipFilterByTimeCreatedLower() {

		// get a timestamp before creating the relationship
		$ts_lower = time() - 1;

		add_entity_relationship($this->guids[0], 'testGetEntitiesFromRelationship', $this->guids[1]);

		// get a timestamp after creating the relationship
		$ts_upper = time() + 1;

		// check that if ts_lower is before the relationship you get the just created entity
		$options = [
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $this->guids[0],
			'relationship_created_time_lower' => $ts_lower
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 1);

		foreach ($es as $e) {
			$this->assertEqual($this->guids[1], $e->guid);
		}

		// check that if ts_lower is after the relationship you get no entities
		$options = [
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $this->guids[0],
			'relationship_created_time_lower' => $ts_upper
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 0);
	}

	/**
	 * Check that you can get entities by filtering them on relationship time created upper
	 */
	public function testGetEntitiesFromRelationshipFilterByTimeCreatedUpper() {

		// get a timestamp before creating the relationship
		$ts_lower = time() - 1;

		add_entity_relationship($this->guids[0], 'testGetEntitiesFromRelationship', $this->guids[1]);

		// get a timestamp after creating the relationship
		$ts_upper = time() + 1;

		// check that if ts_upper is after the relationship you get the just created entity
		$options = [
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $this->guids[0],
			'relationship_created_time_upper' => $ts_upper
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 1);

		foreach ($es as $e) {
			$this->assertEqual($this->guids[1], $e->guid);
		}

		// check that if ts_upper is before the relationship you get no entities
		$options = [
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $this->guids[0],
			'relationship_created_time_upper' => $ts_lower
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 0);
	}

	/**
	 * Check that you can get entities by filtering them on relationship time created lower and upper
	 */
	public function testGetEntitiesFromRelationshipFilterByTimeCreatedLowerAndUpper() {

		// get a timestamp before creating the relationship
		$ts_lower = time() - 1;

		add_entity_relationship($this->guids[0], 'testGetEntitiesFromRelationship', $this->guids[1]);

		// get a timestamp after creating the relationship
		$ts_upper = time() + 1;

		// check that if relationship time created is between lower and upper you get the just created entity
		$options = [
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $this->guids[0],
			'relationship_created_time_lower' => $ts_lower,
			'relationship_created_time_upper' => $ts_upper
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 1);

		foreach ($es as $e) {
			$this->assertEqual($this->guids[1], $e->guid);
		}

		// check that if  ts_lower > ts_upper you get no entities
		$options = [
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $this->guids[0],
			'relationship_created_time_lower' => $ts_upper,
			'relationship_created_time_upper' => $ts_lower
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 0);
	}

}
