<?php
/**
 * Test elgg_get_entities_from_relationship() and
 * elgg_get_entities_from_relationship_count()
 */
class ElggCoreGetEntitiesFromRelationshipTest extends \ElggCoreGetEntitiesBaseTest {

	/** @var ElggEntity */
	public $obj1;

	/** @var ElggEntity */
	public $obj2;

	public function setUp() {
		$this->obj1 = new \ElggObject();
		$this->obj1->save();
		$this->obj2 = new \ElggObject();
		$this->obj2->save();

		// All tests in presence of entity metadata. See #2274
		$this->obj1->test_md = 'test';
		$this->obj2->test_md = 'test';

		add_entity_relationship($this->obj1->guid, 'test', $this->obj2->guid);
	}

	public function tearDown() {
		$this->obj1->delete();
		$this->obj2->delete();
	}

	public function testCanFetchTargetsByRelationshipGuid() {
		$options = array(
			'relationship' => 'test',
			'relationship_guid' => $this->obj1->guid,
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj2->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testCanFetchTargetsByExplicitlyGivingSubject() {
		$options = array(
			'relationship' => 'test',
			'relationship_subject_guid' => $this->obj1->guid,
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj2->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testCanFetchSubjectsByRelationshipGuid() {
		$options = array(
			'relationship' => 'test',
			'relationship_guid' => $this->obj2->guid,
			'inverse_relationship' => true,
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj1->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testCanFetchSubjectsByExplicitlyGivingTarget() {
		$options = array(
			'relationship' => 'test',
			'relationship_target_guid' => $this->obj2->guid,
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj1->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testExplicitSubjectOrTargetDisablesOldApi() {
		$options = array(
			'relationship' => 'test',
			'relationship_subject_guid' => $this->obj1->guid,

			// these ignored
			'relationship_guid' => $this->obj2->guid,
			'inverse_relationship' => true,
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj2->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);

		$options = array(
			'relationship' => 'test',
			'relationship_target_guid' => $this->obj2->guid,

			// these ignored
			'relationship_guid' => $this->obj1->guid,
			'inverse_relationship' => false,
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj1->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testCanFetchTargetsWhileMatchingMetadata() {
		$options = array(
			'relationship' => 'test',
			'relationship_guid' => $this->obj1->guid,
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj2->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);

		$options = array(
			'relationship' => 'test',
			'relationship_subject_guid' => $this->obj1->guid,
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj2->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testCanFetchSubjectsWhileMatchingMetadata() {
		$options = array(
			'relationship' => 'test',
			'relationship_guid' => $this->obj2->guid,
			'inverse_relationship' => true,
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj1->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);

		$options = array(
			'relationship' => 'test',
			'relationship_target_guid' => $this->obj2->guid,
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual($this->obj1->guid, $es[0]->guid);
		$this->assertIdentical(count($es), 1);
	}

	public function testMetadataSearchCanEmptyResultSet() {
		$options = array(
			'relationship' => 'test',
			'relationship_guid' => $this->obj1->guid,
			'metadata_name' => 'test_md',
			'metadata_value' => 'invalid',
		);

		$es = elgg_get_entities_from_relationship($options);
		$this->assertEqual([], $es);
	}

	public function testElggGetEntitiesFromRelationshipCount() {
		$entities = $this->entities;
		$relationships = array();
		$count = count($entities);
		$max = $count - 1;
		$relationship_name = 'test_relationship_' . rand(0, 1000);

		for ($i = 0; $i < $count; $i++) {
			do {
				$popular_entity = $entities[array_rand($entities)];
			} while (array_key_exists($popular_entity->guid, $relationships));

			$relationships[$popular_entity->guid] = array();

			for ($c = 0; $c < $max; $c++) {
				do {
					$fan_entity = $entities[array_rand($entities)];
				} while ($fan_entity->guid == $popular_entity->guid || in_array($fan_entity->guid, $relationships[$popular_entity->guid]));

				$relationships[$popular_entity->guid][] = $fan_entity->guid;
				add_entity_relationship($fan_entity->guid, $relationship_name, $popular_entity->guid);
			}

			$max--;
		}

		$options = array(
			'relationship' => $relationship_name,
			'limit' => $count
		);

		$entities = elgg_get_entities_from_relationship_count($options);

		foreach ($entities as $e) {
			$options = array(
				'relationship' => $relationship_name,
				'limit' => 100,
				'relationship_guid' => $e->guid,
				'inverse_relationship' => true
			);

			$fan_entities = elgg_get_entities_from_relationship($options);

			$this->assertEqual(count($fan_entities), count($relationships[$e->guid]));

			foreach ($fan_entities as $fan_entity) {
				$this->assertTrue(in_array($fan_entity->guid, $relationships[$e->guid]));
				$this->assertNotIdentical(false, check_entity_relationship($fan_entity->guid, $relationship_name, $e->guid));
			}
		}
	}
	
	/**
	 * @link https://github.com/Elgg/Elgg/issues/5775
	 */
	public function testFetchReturnsDistinctResultsWithUnsetRelationshipGuid() {
		$obj3 = new ElggObject();
		$obj3->save();

		add_entity_relationship($this->obj2->guid, 'test_5775', $this->obj1->guid);
		add_entity_relationship($obj3->guid, 'test_5775', $this->obj1->guid);

		$options = array(
			'relationship' => 'test_5775',
			'inverse_relationship' => false,
			'count' => true,
		);
		
		$count = elgg_get_entities_from_relationship($options);
		$this->assertIdentical($count, 1);

		unset($options['count']);
		$objects = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($objects));
		$this->assertIdentical(count($objects), 1);
		
		$obj3->delete();
	}
	
	/**
	 * @link https://github.com/Elgg/Elgg/issues/5775
	 */
	public function testChangesIn5575DontAffectInverseFetches() {
		$obj3 = new ElggObject();
		$obj3->save();

		add_entity_relationship($this->obj2->guid, 'test_5775_inverse', $this->obj1->guid);
		add_entity_relationship($obj3->guid, 'test_5775_inverse', $this->obj1->guid);

		$options = array(
			'relationship' => 'test_5775_inverse',
			'inverse_relationship' => true,
			'count' => true,
		);
		
		$count = elgg_get_entities_from_relationship($options);
		$this->assertIdentical($count, 2);

		unset($options['count']);
		$objects = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($objects));
		$this->assertIdentical(count($objects), 2);
		
		$obj3->delete();
	}

}
