<?php

/**
 * test ElggRelationship
 *
 * @package    Elgg
 * @subpackage Test
 */
class ElggRelationshipTest extends ElggCoreUnitTest {

	/**
	 * @var ElggEntity
	 */
	protected $entity1;
	protected $entity2;
	protected $entity3;

	public function up() {
		_elgg_services()->events->backup();

		$this->entity1 = new ElggObject();
		$this->entity1->subtype = 'elgg_relationship_test';
		$this->entity1->access_id = ACCESS_PUBLIC;
		$this->entity1->save();

		$this->entity2 = new ElggObject();
		$this->entity2->subtype = 'elgg_relationship_test';
		$this->entity2->access_id = ACCESS_PUBLIC;
		$this->entity2->save();

		$this->entity3 = new ElggObject();
		$this->entity3->subtype = 'elgg_relationship_test';
		$this->entity3->access_id = ACCESS_PUBLIC;
		$this->entity3->save();
	}

	public function down() {
		if ($this->entity1) {
			$this->entity1->delete();
		}

		if ($this->entity2) {
			$this->entity2->delete();
		}

		if ($this->entity3) {
			$this->entity3->delete();
		}

		_elgg_services()->events->restore();
	}

	/**
	 * Tests
	 */
	public function testAddRelationship() {
		// test adding a relationship
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');
	}

	public function testRemoveRelationship() {
		// test adding a relationship
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue(remove_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testPreventAddRelationship() {
		// test event handler - should prevent the addition of a relationship
		elgg_register_event_handler('create', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		elgg_unregister_event_handler('create', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testPreventRemoveRelationship() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		elgg_register_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse(remove_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		elgg_unregister_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');
	}

	public function testRelationshipSave() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');
		$old_id = $r->id;

		// note - string because that's how it's returned when getting a new object
		$r->guid_two = (string) $this->entity3->guid;
		$new_id = $r->save();
		$this->assertIsA($new_id, 'int');
		$this->assertNotEqual($new_id, $old_id);

		$test_r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid);
		$this->assertIsA($test_r, 'ElggRelationship');
		$this->assertIdentical($r->guid_one, $test_r->guid_one);
		$this->assertIdentical($r->guid_two, $test_r->guid_two);
		$this->assertIdentical($r->relationship, $test_r->relationship);

		// the original shouldn't exist anymore
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testRelationshipDelete() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($r->delete());
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testRelationshipOnEntityDelete() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		// test deleting entity in guid_one position
		$this->entity1->delete();
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$this->assertTrue(add_entity_relationship($this->entity2->guid, 'test_relationship', $this->entity3->guid));

		// test deleting entity in guid_two position
		$this->entity3->delete();
		$this->assertFalse(check_entity_relationship($this->entity2->guid, 'test_relationship', $this->entity3->guid));
	}

	public function testPreventRelationshipOnEntityDelete() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$this->assertTrue(add_entity_relationship($this->entity2->guid, 'test_relationship', $this->entity1->guid));
		$guid = $this->entity1->guid;

		elgg_register_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$this->assertTrue($this->entity1->delete());

		elgg_unregister_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		// relationships should still be gone as there is no entity
		// despite the fact we have a handler trying to prevent it
		$this->assertFalse(check_entity_relationship($guid, 'test_relationship', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity2->guid, 'test_relationship', $guid));
	}

	public function testEntityMethodAddRelationship() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');
	}

	public function testEntityMethodRemoveRelationship() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->removeRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testEntityMethodDeleteRelationships() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity3->addRelationship($this->entity1->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->deleteRelationships('test_relationship'));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid));

		// inverse relationships should be gone too
		$this->assertFalse(check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid));


		// Repeat above test, but with no relationship - should remove all relationships
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity3->addRelationship($this->entity1->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship2'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity2->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship2'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity3->guid);
		$this->assertIsA($r, 'ElggRelationship');

		$this->assertTrue($this->entity1->deleteRelationships());
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity3->guid));

		// inverse relationships should be gone too
		$this->assertFalse(check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid));
	}
}
